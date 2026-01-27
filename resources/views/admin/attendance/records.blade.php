@extends('layouts.app')

@section('page-title', 'Data Presensi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-calendar-check me-2"></i> Data Presensi Peserta Magang</h2>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Presensi</h5>
                    <h2>{{ $totalRecords }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Hadir</h5>
                    <h2>{{ $totalHadir }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Telat</h5>
                    <h2>{{ $totalTelat }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">Izin</h5>
                    <h2>{{ $totalIzin }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Sakit</h5>
                    <h2>{{ $totalSakit }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">WFH</h5>
                    <h2>{{ $totalWfh }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i> Filter
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Peserta Magang</label>
                    <select name="user_id" class="form-select">
                        <option value="">-- Semua Peserta --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <input type="number" name="month" min="1" max="12" class="form-control"
                           value="{{ $month }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" min="2020" class="form-control"
                           value="{{ $year }}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i> Daftar Presensi -
                {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
            </h5>
        </div>
        <div class="card-body">
            @if($records->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Jarak</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>
                                        <strong>{{ $record->attendance_date->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $record->attendance_date->format('l') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $record->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $record->email }}</small>
                                    </td>
                                    <td>
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
                                                'wfh' => 'WFH'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$record->status] ?? 'secondary' }}">
                                            {{ $statusLabel[$record->status] ?? $record->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($record->checkin_time)
                                            <strong>{{ $record->checkin_time->format('H:i') }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $record->checkin_latitude ? '📍 ' . round($record->checkin_latitude, 4) : '-' }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->checkout_time)
                                            <strong>{{ $record->checkout_time->format('H:i') }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->checkin_distance)
                                            <strong>{{ $record->checkin_distance }}m</strong>
                                            @if($record->checkin_distance <= 400)
                                                <br><small class="text-success">✓ Dalam jangkauan</small>
                                            @else
                                                <br><small class="text-warning">⚠ Luar jangkauan</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->checkin_reason)
                                            <span class="badge bg-light text-dark">
                                                {{ Str::limit($record->checkin_reason, 30) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-end">
                        @if($records->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">← Sebelumnya</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $records->previousPageUrl() }}&user_id={{ $userId }}">
                                    ← Sebelumnya
                                </a>
                            </li>
                        @endif

                        @foreach($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                            @if($page == $records->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}&user_id={{ $userId }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        @if($records->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $records->nextPageUrl() }}&user_id={{ $userId }}">
                                    Selanjutnya →
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Selanjutnya →</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Tidak ada data presensi untuk periode ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
