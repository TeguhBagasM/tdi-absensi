<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSetting;
use App\Models\WfhRecord;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Perform check-in
     */
    public static function checkin($userId, $latitude, $longitude, $reason = null)
    {
        $today = today();

        // Cek apakah sudah ada check-in hari ini
        $existing = AttendanceRecord::where('user_id', $userId)
            ->where('attendance_date', $today)
            ->first();

        if ($existing && $existing->checkin_time) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini pada ' . $existing->checkin_time->format('H:i'),
            ];
        }

        // Hitung jarak dari kantor
        $distance = GeofencingService::calculateDistance(
            $latitude,
            $longitude,
            config('attendance.office_latitude', -6.9248406),
            config('attendance.office_longitude', 107.6586951)
        );

        $isWithinGeofence = $distance <= config('attendance.geofence_radius_meters', 400);
        $currentTime = now();
        $lateAfterTime = Carbon::createFromFormat('H:i', AttendanceSetting::getValue('late_after_time', '09:10'));

        $data = [
            'user_id' => $userId,
            'attendance_date' => $today,
            'checkin_time' => $currentTime->format('H:i:s'),
            'checkin_latitude' => $latitude,
            'checkin_longitude' => $longitude,
            'checkin_distance' => (int) $distance,
        ];

        // Determine status
        if ($isWithinGeofence) {
            // Check-in dari kantor
            if ($currentTime->format('H:i') > $lateAfterTime->format('H:i')) {
                // Telat
                $data['status'] = 'telat';
                $data['checkin_reason'] = $reason;
                $data['approval_status'] = 'pending'; // Butuh approval karena ada alasan
            } else {
                // Hadir
                $data['status'] = 'hadir';
                $data['approval_status'] = 'approved'; // Auto approve
            }
        } else {
            // Check-in dari luar kantor, tidak bisa
            return [
                'success' => false,
                'message' => 'Anda berada ' . round($distance) . ' meter dari kantor. Jarak minimum adalah ' . config('attendance.geofence_radius_meters', 400) . ' meter. Gunakan manual check-in.',
                'distance' => round($distance),
            ];
        }

        $record = AttendanceRecord::updateOrCreate(
            [
                'user_id' => $userId,
                'attendance_date' => $today,
            ],
            $data
        );

        return [
            'success' => true,
            'message' => $data['status'] === 'hadir'
                ? 'Check-in berhasil! Status: Hadir'
                : 'Check-in berhasil! Status: Telat (menunggu approval)',
            'record' => $record,
        ];
    }

    /**
     * Perform manual check-in (izin, sakit, WFH)
     */
    public static function manualCheckin($userId, $status, $reason, $filePath = null)
    {
        $today = today();

        // Validasi WFH limit
        if ($status === 'wfh') {
            $maxWfh = (int) AttendanceSetting::getValue('max_wfh_per_week', 1);
            $wfhThisWeek = WfhRecord::getWfhCountThisWeek($userId);

            if ($wfhThisWeek >= $maxWfh) {
                return [
                    'success' => false,
                    'message' => "Anda sudah mencapai batas WFH ({$maxWfh}x) minggu ini.",
                ];
            }

            // Increment WFH record
            WfhRecord::incrementThisWeek($userId);
        }

        // Validasi izin dan sakit memerlukan file
        if (in_array($status, ['izin', 'sakit']) && !$filePath) {
            return [
                'success' => false,
                'message' => "Bukti ({$status}) wajib diupload.",
            ];
        }

        $data = [
            'user_id' => $userId,
            'attendance_date' => $today,
            'status' => $status,
            'checkin_reason' => $reason,
            'file_path' => $filePath,
            'approval_status' => 'pending', // Manual check-in harus di-approve admin
        ];

        $record = AttendanceRecord::updateOrCreate(
            [
                'user_id' => $userId,
                'attendance_date' => $today,
            ],
            $data
        );

        return [
            'success' => true,
            'message' => "Presensi {$status} berhasil dicatat (menunggu approval)",
            'record' => $record,
        ];
    }

    /**
     * Perform check-out
     */
    public static function checkout($userId)
    {
        $today = today();

        $record = AttendanceRecord::where('user_id', $userId)
            ->where('attendance_date', $today)
            ->first();

        if (!$record) {
            return [
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini.',
            ];
        }

        if ($record->checkout_time) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan check-out pada ' . $record->checkout_time->format('H:i'),
            ];
        }

        $record->update([
            'checkout_time' => now()->format('H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => 'Check-out berhasil pada ' . now()->format('H:i'),
            'record' => $record,
        ];
    }

    /**
     * Get attendance summary untuk periode tertentu
     */
    public static function getAttendanceSummary($userId, $startDate, $endDate)
    {
        $records = AttendanceRecord::where('user_id', $userId)
            ->dateRange($startDate, $endDate)
            ->orderBy('attendance_date')
            ->get();

        $summary = [
            'hadir' => 0,
            'telat' => 0,
            'izin' => 0,
            'sakit' => 0,
            'wfh' => 0,
        ];

        foreach ($records as $record) {
            if (isset($summary[$record->status])) {
                $summary[$record->status]++;
            }
        }

        return [
            'records' => $records,
            'summary' => $summary,
        ];
    }

    /**
     * Get pending attendance untuk admin approval
     */
    public static function getPendingApprovals($userId = null)
    {
        $query = AttendanceRecord::where('approval_status', 'pending')
            ->with('user', 'user.division', 'user.jobRole')
            ->orderByDesc('attendance_date');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get();
    }

    /**
     * Approve attendance record
     */
    public static function approveAttendance($recordId, $approvedBy)
    {
        $record = AttendanceRecord::findOrFail($recordId);

        $record->update([
            'approval_status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Presensi berhasil disetujui',
            'record' => $record,
        ];
    }

    /**
     * Reject attendance record
     */
    public static function rejectAttendance($recordId, $approvedBy, $reason = null)
    {
        $record = AttendanceRecord::findOrFail($recordId);

        $record->update([
            'approval_status' => 'rejected',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'checkin_reason' => $reason,
        ]);

        // Jika reject karena WFH, decrement WFH count
        if ($record->status === 'wfh') {
            $wfhRecord = WfhRecord::where('user_id', $record->user_id)
                ->where('week_starting', $record->attendance_date->startOfWeek()->toDateString())
                ->first();

            if ($wfhRecord && $wfhRecord->count > 0) {
                $wfhRecord->decrement('count');
            }
        }

        return [
            'success' => true,
            'message' => 'Presensi berhasil ditolak',
            'record' => $record,
        ];
    }
}
