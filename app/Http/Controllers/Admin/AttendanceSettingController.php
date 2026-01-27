<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

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
            $setting->save();

            // Clear cache
            \Illuminate\Support\Facades\Cache::forget("attendance_setting_{$setting_key}");

            return response()->json([
                'success' => true,
                'message' => "Setting '{$setting_key}' berhasil diupdate."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
}
