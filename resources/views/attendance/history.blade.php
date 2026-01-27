@extends('layouts.app')

@section('page-title', 'Riwayat Presensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-12">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h3 class="card-title">{{ $summary['hadir'] ?? 0 }}</h3>
                            <p class="card-text">Hadir</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-warning text-white">
                        <div class="card-body">
                            <h3 class="card-title">{{ $summary['telat'] ?? 0 }}</h3>
                            <p class="card-text">Telat</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <h3 class="card-title">{{ $summary['izin'] ?? 0 }}</h3>
                            <p class="card-text">Izin</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-danger text-white">
                        <div class="card-body">
                            <h3 class="card-title">{{ $summary['sakit'] ?? 0 }}</h3>
                            <p class="card-text">Sakit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-history me-2"></i> Riwayat Presensi Bulan
                        <select id="month-select" class="form-select d-inline w-auto" style="width: 150px;" onchange="filterMonth()">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        <select id="year-select" class="form-select d-inline w-auto" style="width: 120px;" onchange="filterMonth()">
                            @for($y = now()->year - 2; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </h4>
                    <a href="{{ route('attendance.checkin') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Check-in
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Jarak (m)</th>
                                    <th>Keterangan</th>
                                    <th>Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>
                                        {{ $record->attendance_date->format('d/m/Y') }}
                                        <br/>
                                        <small class="text-muted">{{ $record->attendance_date->format('l') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{
                                            $record->status === 'hadir' ? 'success' :
                                            ($record->status === 'telat' ? 'warning' :
                                            ($record->status === 'izin' ? 'info' :
                                            ($record->status === 'sakit' ? 'danger' : 'secondary')))
                                        }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $record->checkin_time ? $record->checkin_time->format('H:i') : '-' }}
                                    </td>
                                    <td>
                                        {{ $record->checkout_time ? $record->checkout_time->format('H:i') : '-' }}
                                    </td>
                                    <td>
                                        {{ $record->checkin_distance ? $record->checkin_distance : '-' }}
                                    </td>
                                    <td>
                                        @if($record->checkin_reason)
                                            <small>{{ Str::limit($record->checkin_reason, 50) }}</small>
                                            @if($record->file_path)
                                                <br/>
                                                <a href="{{ Storage::url($record->file_path) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-download me-1"></i> File
                                                </a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->approval_status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Disetujui
                                            </span>
                                        @elseif($record->approval_status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">Tidak ada data presensi untuk bulan ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterMonth() {
        const month = document.getElementById('month-select').value;
        const year = document.getElementById('year-select').value;
        window.location.href = `{{ route('attendance.history') }}?month=${month}&year=${year}`;
    }
</script>
@endsection
