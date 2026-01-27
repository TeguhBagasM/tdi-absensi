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
            } else {
                // Hadir
                $data['status'] = 'hadir';
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
                ? 'Check-in berhasil! Silakan checkout saat pulang.'
                : 'Check-in berhasil sebagai telat! Silakan checkout saat pulang.',
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
            'checkin_time' => now()->format('H:i:s'),
            // Izin/Sakit/WFH tidak perlu checkout
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
            'message' => "Presensi {$status} berhasil dicatat.",
            'record' => $record,
        ];
    }

    /**
     * Perform check-out (for hadir/telat status)
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

        // Hanya status hadir dan telat yang bisa checkout
        if (!in_array($record->status, ['hadir', 'telat'])) {
            return [
                'success' => false,
                'message' => 'Anda tidak perlu checkout untuk status ' . $record->status,
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
     * Perform manual check-out (WFH)
     */
    public static function manualCheckout($userId)
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

        if ($record->status !== 'wfh') {
            return [
                'success' => false,
                'message' => 'Checkout manual hanya untuk status WFH.',
            ];
        }

        $record->update([
            'checkout_time' => now()->format('H:i:s'),
        ]);

        return [
            'success' => true,
            'message' => 'Check-out WFH berhasil pada ' . now()->format('H:i'),
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
}
