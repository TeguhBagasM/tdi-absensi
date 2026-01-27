@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-info border-0 shadow-sm">
                <h4 class="mb-1">
                    <i class="fas fa-wave-square me-2"></i> Selamat datang, {{ Auth::user()->name }}!
                </h4>
                <p class="mb-0 text-muted">
                    Dashboard Presensi - Monitor status dan riwayat kehadiran Anda
                </p>
            </div>
        </div>
    </div>

    <!-- Today Status Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i> Status Hari Ini ({{ now()->format('d M Y') }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($todayAttendance)
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                @php
                                    $statusColors = [
                                        'hadir' => 'success',
                                        'telat' => 'warning',
                                        'izin' => 'secondary',
                                        'sakit' => 'danger',
                                        'wfh' => 'info'
                                    ];
                                    $statusLabel = [
                                        'hadir' => 'Hadir',
                                        'telat' => 'Telat',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'wfh' => 'Work From Home'
                                    ];
                                    $statusIcon = [
                                        'hadir' => 'check-circle',
                                        'telat' => 'exclamation-circle',
                                        'izin' => 'info-circle',
                                        'sakit' => 'times-circle',
                                        'wfh' => 'home'
                                    ];
                                @endphp
                                <div class="mb-3">
                                    <p class="text-muted mb-1">Status Presensi:</p>
                                    <h4 class="mb-0">
                                        <span class="badge bg-{{ $statusColors[$todayAttendance->status] ?? 'secondary' }} p-2">
                                            <i class="fas fa-{{ $statusIcon[$todayAttendance->status] ?? 'circle' }} me-2"></i>
                                            {{ $statusLabel[$todayAttendance->status] ?? $todayAttendance->status }}
                                        </span>
                                    </h2>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-primary bg-opacity-10 rounded mb-2">
                                            <p class="text-muted mb-1 small">Check-in:</p>
                                            <h5 class="mb-0">
                                                @if($todayAttendance->checkin_time)
                                                    {{ $todayAttendance->checkin_time->format('H:i') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-success bg-opacity-10 rounded mb-2">
                                            <p class="text-muted mb-1 small">Check-out:</p>
                                            <h5 class="mb-0">
                                                @if($todayAttendance->checkout_time)
                                                    {{ $todayAttendance->checkout_time->format('H:i') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-info-circle me-2"></i> Anda belum melakukan check-in hari ini.
                            <a href="{{ route('attendance.checkin') }}" class="alert-link"> Lakukan check-in sekarang</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-3">
                    <h3 class="text-success mb-1">{{ $monthStats['hadir'] }}</h3>
                    <small class="text-muted">Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-3">
                    <h3 class="text-warning mb-1">{{ $monthStats['telat'] }}</h3>
                    <small class="text-muted">Telat</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-3">
                    <h3 class="text-secondary mb-1">{{ $monthStats['izin'] }}</h3>
                    <small class="text-muted">Izin</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-3">
                    <h3 class="text-danger mb-1">{{ $monthStats['sakit'] }}</h3>
                    <small class="text-muted">Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-3">
                    <h3 class="text-info mb-1">{{ $monthStats['wfh'] }}</h3>
                    <small class="text-muted">WFH</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body p-3">
                    <h3 class="text-primary mb-1">
                        {{ $monthStats['hadir'] + $monthStats['telat'] + $monthStats['izin'] + $monthStats['sakit'] + $monthStats['wfh'] }}
                    </h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i> Statistik Presensi Bulan Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px;">
                        <canvas id="monthPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i> Trend 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px;">
                        <canvas id="last7DaysChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Comparison Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Perbandingan 4 Minggu Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="last4WeeksChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-link me-2"></i> Quick Links
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('attendance.checkin') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Check-in
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('attendance.history') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-history me-2"></i> Riwayat Presensi
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('attendance.manual') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-plus-circle me-2"></i> Manual Check-in
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-secondary w-100 mb-2" onclick="location.reload()">
                                <i class="fas fa-sync me-2"></i> Refresh
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
    const colors = {
        hadir: '#198754',
        telat: '#ffc107',
        izin: '#6c757d',
        sakit: '#dc3545',
        wfh: '#0dcaf0'
    };

    // Month Pie Chart
    const monthCtx = document.getElementById('monthPieChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Telat', 'Izin', 'Sakit', 'WFH'],
            datasets: [{
                data: [
                    {{ $monthStats['hadir'] }},
                    {{ $monthStats['telat'] }},
                    {{ $monthStats['izin'] }},
                    {{ $monthStats['sakit'] }},
                    {{ $monthStats['wfh'] }}
                ],
                backgroundColor: [
                    colors.hadir,
                    colors.telat,
                    colors.izin,
                    colors.sakit,
                    colors.wfh
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Last 7 Days Line Chart
    const last7DaysCtx = document.getElementById('last7DaysChart').getContext('2d');
    new Chart(last7DaysCtx, {
        type: 'line',
        data: {
            labels: {!! $chartData['last7Days'] !!},
            datasets: [{
                label: 'Presensi',
                data: {!! $chartData['last7DaysData'] !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 1 ? '✓' : '✗';
                        }
                    }
                }
            }
        }
    });

    // Last 4 Weeks Bar Chart
    const last4WeeksCtx = document.getElementById('last4WeeksChart').getContext('2d');
    new Chart(last4WeeksCtx, {
        type: 'bar',
        data: {
            labels: {!! $chartData['last4Weeks'] !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! $chartData['last4WeeksHadir'] !!},
                    backgroundColor: colors.hadir,
                    borderRadius: 5,
                    borderSkipped: false
                },
                {
                    label: 'Telat',
                    data: {!! $chartData['last4WeeksTelat'] !!},
                    backgroundColor: colors.telat,
                    borderRadius: 5,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
