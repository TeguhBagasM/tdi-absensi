<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class AttendanceSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    /**
     * Show settings page
     */
    public function index()
    {
        $settings = AttendanceSetting::orderBy('setting_key')->get();

        return view('admin.attendance.settings', compact('settings'));
    }

    /**
     * Update setting
     */
    public function update(Request $request, AttendanceSetting $setting)
    {
        $rules = ['setting_value' => 'required'];

        // Validasi berdasarkan data type
        if ($setting->data_type === 'time') {
            $rules['setting_value'] = 'required|date_format:H:i';
        } elseif ($setting->data_type === 'integer') {
            $rules['setting_value'] = 'required|integer|min:0';
        } elseif ($setting->data_type === 'decimal') {
            $rules['setting_value'] = 'required|numeric';
        }

        $request->validate($rules);

        $setting->update([
            'setting_value' => $request->setting_value,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Setting '{$setting->setting_key}' berhasil diupdate.");
    }

    /**
     * Get all settings (untuk frontend config)
     */
    public function getAll()
    {
        $settings = AttendanceSetting::getAllAsArray();

        return response()->json($settings);
    }
}
