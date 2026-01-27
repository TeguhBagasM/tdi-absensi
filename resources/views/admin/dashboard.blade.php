@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-primary border-0 shadow-sm">
                <h4 class="mb-1">
                    <i class="fas fa-wave-square me-2"></i> Selamat datang, {{ Auth::user()->name }}!
                </h4>
                <p class="mb-0 text-muted">
                    Dashboard admin - Pantau statistik dan kelola sistem presensi
                </p>
            </div>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Total Peserta Magang</h6>
                            <h2 class="mb-0 text-primary">{{ $totalUsers }}</h2>
                        </div>
                        <div class="fs-3 text-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Menunggu Persetujuan</h6>
                            <h2 class="mb-0 text-warning">{{ $pendingUsers }}</h2>
                        </div>
                        <div class="fs-3 text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Presensi Hari Ini</h6>
                            <h2 class="mb-0 text-success">{{ $totalAttendanceToday }}</h2>
                        </div>
                        <div class="fs-3 text-success">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Hari Ini</h6>
                            <h2 class="mb-0 text-info">{{ now()->format('d/m/Y') }}</h2>
                        </div>
                        <div class="fs-3 text-info">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance Status -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i> Status Presensi Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px;">
                        <canvas id="todayPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i> Detail Presensi Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h4 class="text-success mb-1">{{ $todayStats['hadir'] }}</h4>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h4 class="text-warning mb-1">{{ $todayStats['telat'] }}</h4>
                                <small class="text-muted">Telat</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-secondary bg-opacity-10 rounded">
                                <h4 class="text-secondary mb-1">{{ $todayStats['izin'] }}</h4>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h4 class="text-danger mb-1">{{ $todayStats['sakit'] }}</h4>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-info bg-opacity-10 rounded">
                                <h4 class="text-info mb-1">{{ $todayStats['wfh'] }}</h4>
                                <small class="text-muted">WFH</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-primary bg-opacity-10 rounded">
                                <h4 class="text-primary mb-1">
                                    {{ $todayStats['hadir'] + $todayStats['telat'] + $todayStats['izin'] + $todayStats['sakit'] + $todayStats['wfh'] }}
                                </h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Statistik Presensi Bulan Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px;">
                        <canvas id="monthBarChart"></canvas>
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

    <!-- Yearly Trend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i> Trend Presensi 12 Bulan Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="last12MonthsChart"></canvas>
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
                            <a href="{{ route('admin.attendance.records') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-list me-2"></i> Lihat Data Presensi
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.attendance.settings') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-cog me-2"></i> Pengaturan Presensi
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('users.approvals') }}" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-user-check me-2"></i> Persetujuan User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                                <i class="fas fa-users me-2"></i> Manajemen User
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
    // Colors
    const colors = {
        hadir: '#198754',
        telat: '#ffc107',
        izin: '#6c757d',
        sakit: '#dc3545',
        wfh: '#0dcaf0'
    };

    // Today's Status Pie Chart
    const todayCtx = document.getElementById('todayPieChart').getContext('2d');
    new Chart(todayCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Telat', 'Izin', 'Sakit', 'WFH'],
            datasets: [{
                data: [
                    {{ $todayStats['hadir'] }},
                    {{ $todayStats['telat'] }},
                    {{ $todayStats['izin'] }},
                    {{ $todayStats['sakit'] }},
                    {{ $todayStats['wfh'] }}
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

    // Monthly Bar Chart
    const monthCtx = document.getElementById('monthBarChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Telat', 'Izin', 'Sakit', 'WFH'],
            datasets: [{
                label: 'Jumlah Presensi',
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
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'x',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
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
                label: 'Total Presensi',
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
                    beginAtZero: true
                }
            }
        }
    });

    // Last 12 Months Line Chart
    const last12MonthsCtx = document.getElementById('last12MonthsChart').getContext('2d');
    new Chart(last12MonthsCtx, {
        type: 'line',
        data: {
            labels: {!! $chartData['last12Months'] !!},
            datasets: [{
                label: 'Total Presensi per Bulan',
                data: {!! $chartData['last12MonthsData'] !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#198754',
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
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
