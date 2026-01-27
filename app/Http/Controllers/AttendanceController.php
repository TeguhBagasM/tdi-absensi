<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSetting;
use App\Services\AttendanceService;
use App\Services\GeofencingService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('peserta_magang')->only(['checkin', 'checkout', 'history', 'manualCheckin']);
    }

    /**
     * Show check-in page
     */
    public function checkin()
    {
        $geofence = GeofencingService::getOfficeGeofence();
        $todayAttendance = AttendanceRecord::forUserToday(auth()->id())->first();

        return view('attendance.checkin', compact('geofence', 'todayAttendance'));
    }

    /**
     * Process check-in via AJAX
     */
    public function storeCheckin(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'reason' => 'nullable|string|max:500',
        ]);

        $result = AttendanceService::checkin(
            auth()->id(),
            $request->latitude,
            $request->longitude,
            $request->reason
        );

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    /**
     * Show manual check-in modal/page
     */
    public function manualCheckin()
    {
        $todayAttendance = AttendanceRecord::forUserToday(auth()->id())->first();
        $maxWfhPerWeek = AttendanceSetting::getValue('max_wfh_per_week', 1);

        return view('attendance.manual-checkin', compact('todayAttendance', 'maxWfhPerWeek'));
    }

    /**
     * Process manual check-in
     */
    public function storeManualCheckin(Request $request)
    {
        $rules = [
            'status' => 'required|in:izin,sakit,wfh',
            'reason' => 'required|string|max:1000',
        ];

        if (in_array($request->status, ['izin', 'sakit'])) {
            $rules['file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'; // max 5MB
        }

        $request->validate($rules);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('attendance-files', 'public');
        }

        $result = AttendanceService::manualCheckin(
            auth()->id(),
            $request->status,
            $request->reason,
            $filePath
        );

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    /**
     * Process check-out via AJAX
     */
    public function storeCheckout(Request $request)
    {
        $result = AttendanceService::checkout(auth()->id());

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    /**
     * Process manual check-out for WFH via AJAX
     */
    public function storeManualCheckout(Request $request)
    {
        $result = AttendanceService::manualCheckout(auth()->id());

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    /**
     * Show attendance history
     */
    public function history()
    {
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $data = AttendanceService::getAttendanceSummary(
            auth()->id(),
            $startDate,
            $endDate
        );

        $records = $data['records'];
        $summary = $data['summary'];

        return view('attendance.history', compact('records', 'summary', 'month', 'year'));
    }

    /**
     * Get today's attendance status (for dashboard widget)
     */
    public function getTodayStatus()
    {
        $attendance = AttendanceRecord::forUserToday(auth()->id())->first();

        return response()->json([
            'attendance' => $attendance,
            'has_checkin' => $attendance && $attendance->checkin_time,
            'has_checkout' => $attendance && $attendance->checkout_time,
        ]);
    }
}
