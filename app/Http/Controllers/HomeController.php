<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = auth()->id();

        // Today's Status
        $todayAttendance = AttendanceRecord::where('user_id', $userId)
            ->whereDate('attendance_date', today())
            ->first();

        // This Month Statistics
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthStats = [
            'hadir' => AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->where('status', 'hadir')
                ->count(),
            'telat' => AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->where('status', 'telat')
                ->count(),
            'izin' => AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->where('status', 'izin')
                ->count(),
            'sakit' => AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->where('status', 'sakit')
                ->count(),
            'wfh' => AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->where('status', 'wfh')
                ->count(),
        ];

        // Trend Presensi 7 Hari Terakhir
        $last7Days = [];
        $last7DaysData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $status = AttendanceRecord::where('user_id', $userId)
                ->whereDate('attendance_date', $date)
                ->first();
            $last7Days[] = Carbon::createFromFormat('Y-m-d', $date)->format('d M');
            $last7DaysData[] = $status ? 1 : 0; // 1 = presensi, 0 = tidak
        }

        // Trend Statistik 4 Minggu Terakhir
        $last4Weeks = [];
        $last4WeeksHadir = [];
        $last4WeeksTelat = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $label = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
            $hadir = AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$weekStart, $weekEnd])
                ->where('status', 'hadir')
                ->count();
            $telat = AttendanceRecord::where('user_id', $userId)
                ->whereBetween('attendance_date', [$weekStart, $weekEnd])
                ->where('status', 'telat')
                ->count();

            $last4Weeks[] = $label;
            $last4WeeksHadir[] = $hadir;
            $last4WeeksTelat[] = $telat;
        }

        $chartData = [
            'last7Days' => json_encode($last7Days),
            'last7DaysData' => json_encode($last7DaysData),
            'last4Weeks' => json_encode($last4Weeks),
            'last4WeeksHadir' => json_encode($last4WeeksHadir),
            'last4WeeksTelat' => json_encode($last4WeeksTelat),
        ];

        return view('home', compact(
            'todayAttendance',
            'monthStats',
            'chartData'
        ));
    }

    public function produk() {
        return "ini contoh untuk halaman produk";
    }
}
