@extends('layouts.app')

@section('page-title', 'Riwayat Presensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-12">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-check-circle"></i></div>
                            <h3 class="card-title" style="color: #3b82f6; font-weight: 700;">{{ $summary['hadir'] ?? 0 }}</h3>
                            <p class="card-text" style="color: #6b7280; margin: 0;">Hadir</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-clock"></i></div>
                            <h3 class="card-title" style="color: #3b82f6; font-weight: 700;">{{ $summary['telat'] ?? 0 }}</h3>
                            <p class="card-text" style="color: #6b7280; margin: 0;">Telat</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-envelope"></i></div>
                            <h3 class="card-title" style="color: #3b82f6; font-weight: 700;">{{ $summary['izin'] ?? 0 }}</h3>
                            <p class="card-text" style="color: #6b7280; margin: 0;">Izin</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-heartbeat"></i></div>
                            <h3 class="card-title" style="color: #3b82f6; font-weight: 700;">{{ $summary['sakit'] ?? 0 }}</h3>
                            <p class="card-text" style="color: #6b7280; margin: 0;">Sakit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="col-md-12">
            <div class="card" style="border: 1px solid #e5e7eb; background: white; border-radius: 8px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                    <h4 class="mb-0" style="color: #1f2937; font-weight: 600;">
                        <i class="fas fa-history me-2" style="color: #3b82f6;"></i> Riwayat Presensi Bulan
                        <select id="month-select" class="form-select d-inline w-auto" style="width: 150px; border-color: #d1d5db; border-radius: 6px;" onchange="filterMonth()">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        <select id="year-select" class="form-select d-inline w-auto" style="width: 120px; border-color: #d1d5db; border-radius: 6px;" onchange="filterMonth()">
                            @for($y = now()->year - 2; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </h4>
                    <a href="{{ route('attendance.checkin') }}" class="btn btn-sm" style="background: #3b82f6; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Check-in
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom: 0;">
                            <thead style="background: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                <tr>
                                    <th style="color: #4b5563; font-weight: 600; border: none; padding: 12px;">Tanggal</th>
                                    <th style="color: #4b5563; font-weight: 600; border: none; padding: 12px;">Status</th>
                                    <th style="color: #4b5563; font-weight: 600; border: none; padding: 12px;">Jam Masuk</th>
                                    <th style="color: #4b5563; font-weight: 600; border: none; padding: 12px;">Jam Pulang</th>
                                    <th style="color: #4b5563; font-weight: 600; border: none; padding: 12px;">Jarak (m)</th>
                                    <th style="color: #4b5563; font-weight: 600; border: none; padding: 12px;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        <strong>{{ $record->attendance_date->format('d/m/Y') }}</strong>
                                        <br/>
                                        <small style="color: #9ca3af;">{{ $record->attendance_date->format('l') }}</small>
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @php
                                            $statusConfig = [
                                                'hadir' => ['text' => 'Hadir', 'bg' => '#dbeafe', 'color' => '#0369a1'],
                                                'telat' => ['text' => 'Telat', 'bg' => '#fef3c7', 'color' => '#b45309'],
                                                'izin' => ['text' => 'Izin', 'bg' => '#ede9fe', 'color' => '#6d28d9'],
                                                'sakit' => ['text' => 'Sakit', 'bg' => '#fee2e2', 'color' => '#991b1b']
                                            ];
                                            $config = $statusConfig[$record->status] ?? ['text' => ucfirst($record->status), 'bg' => '#f3f4f6', 'color' => '#4b5563'];
                                        @endphp
                                        <span style="background: {{ $config['bg'] }}; color: {{ $config['color'] }}; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
                                            {{ $config['text'] }}
                                        </span>
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        {{ $record->checkin_time ? $record->checkin_time->format('H:i') : '-' }}
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        {{ $record->checkout_time ? $record->checkout_time->format('H:i') : '-' }}
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        {{ $record->checkin_distance ? $record->checkin_distance : '-' }}
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @if($record->checkin_reason)
                                            <small style="color: #6b7280;">{{ Str::limit($record->checkin_reason, 50) }}</small>
                                            @if($record->file_path)
                                                <br/>
                                                <a href="{{ Storage::url($record->file_path) }}" target="_blank" class="btn btn-sm" style="background: transparent; color: #3b82f6; border: 1px solid #bfdbfe; border-radius: 6px; padding: 4px 8px; margin-top: 4px; font-size: 0.75rem; font-weight: 500;">
                                                    <i class="fas fa-download me-1"></i> File
                                                </a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4" style="border: none;">
                                        <i class="fas fa-inbox" style="font-size: 2rem; color: #d1d5db;"></i>
                                        <p style="color: #9ca3af; margin-top: 12px; margin-bottom: 0;">Tidak ada data presensi untuk bulan ini.</p>
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
