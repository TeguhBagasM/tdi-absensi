<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    public function index()
    {
        // Statistik Dasar
        $totalUsers = User::where('is_approved', 1)->count();
        $pendingUsers = User::where('is_approved', 0)->count();
        $totalAttendanceToday = AttendanceRecord::whereDate('attendance_date', today())->count();

        // Statistik Presensi Hari Ini
        $todayStats = [
            'hadir' => AttendanceRecord::whereDate('attendance_date', today())->where('status', 'hadir')->count(),
            'telat' => AttendanceRecord::whereDate('attendance_date', today())->where('status', 'telat')->count(),
            'izin' => AttendanceRecord::whereDate('attendance_date', today())->where('status', 'izin')->count(),
            'sakit' => AttendanceRecord::whereDate('attendance_date', today())->where('status', 'sakit')->count(),
            'wfh' => AttendanceRecord::whereDate('attendance_date', today())->where('status', 'wfh')->count(),
        ];

        // Statistik Presensi Bulan Ini
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $monthStats = [
            'hadir' => AttendanceRecord::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status', 'hadir')->count(),
            'telat' => AttendanceRecord::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status', 'telat')->count(),
            'izin' => AttendanceRecord::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status', 'izin')->count(),
            'sakit' => AttendanceRecord::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status', 'sakit')->count(),
            'wfh' => AttendanceRecord::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status', 'wfh')->count(),
        ];

        // Trend Presensi 7 Hari Terakhir
        $last7Days = [];
        $last7DaysData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = AttendanceRecord::whereDate('attendance_date', $date)->count();
            $last7Days[] = Carbon::createFromFormat('Y-m-d', $date)->format('d M');
            $last7DaysData[] = $count;
        }

        // Trend Presensi 12 Bulan
        $last12Months = [];
        $last12MonthsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabel = $month->format('M Y');
            $count = AttendanceRecord::whereBetween('attendance_date', [
                $month->startOfMonth(),
                $month->endOfMonth()
            ])->count();
            $last12Months[] = $monthLabel;
            $last12MonthsData[] = $count;
        }

        // Data untuk Chart
        $chartData = [
            'todayStats' => $todayStats,
            'monthStats' => $monthStats,
            'last7Days' => json_encode($last7Days),
            'last7DaysData' => json_encode($last7DaysData),
            'last12Months' => json_encode($last12Months),
            'last12MonthsData' => json_encode($last12MonthsData),
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingUsers',
            'totalAttendanceToday',
            'todayStats',
            'monthStats',
            'chartData'
        ));
    }
}
