<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    /**
     * Show settings page
     */
    public function index()
    {
        $settings = AttendanceSetting::getAllAsArray();
        $totalUsers = \App\Models\User::where('is_approved', 1)->count();
        $todayAttendance = \App\Models\AttendanceRecord::whereDate('attendance_date', today())->count();
        // Removed: pendingApprovals - no approval workflow

        return view('admin.attendance.settings', compact('settings', 'totalUsers', 'todayAttendance'));
    }

    /**
     * Update setting via AJAX (JSON request)
     */
    public function update(Request $request)
    {
        $setting_key = $request->input('setting_key');
        $setting_value = $request->input('setting_value');
        $data_type = $request->input('data_type');

        // Log request untuk debugging
        Log::info('Update Setting Request:', [
            'key' => $setting_key,
            'value' => $setting_value,
            'type' => $data_type,
            'user' => auth()->id()
        ]);

        // Validate required fields
        $request->validate([
            'setting_key' => 'required|string',
            'setting_value' => 'required',
            'data_type' => 'required|string|in:time,integer,decimal,string',
        ]);

        // Validate based on data type
        if ($data_type === 'time') {
            $request->validate([
                'setting_value' => 'required|date_format:H:i',
            ]);
        } elseif ($data_type === 'integer') {
            $request->validate([
                'setting_value' => 'required|integer|min:0',
            ]);
        } elseif ($data_type === 'decimal') {
            $request->validate([
                'setting_value' => 'required|numeric',
            ]);
        } else {
            $request->validate([
                'setting_value' => 'required|string|max:255',
            ]);
        }

        try {
            // Find or create setting
            $setting = AttendanceSetting::where('setting_key', $setting_key)->first();

            if (!$setting) {
                $setting = new AttendanceSetting();
                $setting->setting_key = $setting_key;
            }

            $setting->setting_value = $setting_value;
            $setting->data_type = $data_type;
            $setting->updated_by = auth()->id();

            // Force save dan pastikan benar-benar tersimpan
            $saved = $setting->save();

            if (!$saved) {
                throw new \Exception("Gagal menyimpan setting ke database");
            }

            // Clear semua cache terkait
            \Illuminate\Support\Facades\Cache::forget("attendance_setting_{$setting_key}");
            \Illuminate\Support\Facades\Cache::forget('attendance_settings_all');

            // Verify data tersimpan dengan query ulang
            $verifyUpdate = AttendanceSetting::where('setting_key', $setting_key)->first();

            return response()->json([
                'success' => true,
                'message' => "Setting '{$setting_key}' berhasil diupdate menjadi '{$verifyUpdate->setting_value}'",
                'data' => [
                    'key' => $setting_key,
                    'value' => $verifyUpdate->setting_value,
                    'type' => $verifyUpdate->data_type
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get all settings as JSON
     */
    public function getAll()
    {
        $settings = AttendanceSetting::getAllAsArray();

        return response()->json($settings);
    }

    /**
     * Show attendance records for all peserta magang
     */
    public function records()
    {
        $month = request('month', now()->month);
        $year = request('year', now()->year);
        $userId = request('user_id');

        $query = \App\Models\AttendanceRecord::query();

        // Filter by month and year
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $query->whereBetween('attendance_date', [$startDate, $endDate]);

        // Filter by user jika ada
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Join dengan user untuk mendapat nama
        $records = $query->join('users', 'attendance_records.user_id', '=', 'users.id')
            ->select('attendance_records.*', 'users.name', 'users.email')
            ->orderBy('attendance_records.attendance_date', 'desc')
            ->paginate(50);

        // Get all users untuk filter dropdown
        $users = \App\Models\User::where('is_approved', 1)->orderBy('name')->get();

        // Summary statistics
        $totalRecords = \App\Models\AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])->count();
        $totalHadir = \App\Models\AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->where('status', 'hadir')->count();
        $totalTelat = \App\Models\AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->where('status', 'telat')->count();
        $totalIzin = \App\Models\AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->where('status', 'izin')->count();
        $totalSakit = \App\Models\AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->where('status', 'sakit')->count();
        $totalWfh = \App\Models\AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->where('status', 'wfh')->count();

        return view('admin.attendance.records', compact(
            'records', 'users', 'month', 'year', 'userId',
            'totalRecords', 'totalHadir', 'totalTelat', 'totalIzin', 'totalSakit', 'totalWfh'
        ));
    }
}
