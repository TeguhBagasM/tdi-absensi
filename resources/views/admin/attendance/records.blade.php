@extends('layouts.app')

@section('page-title', 'Data Presensi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 style="color: #1f2937; font-weight: 600;"><i class="fas fa-calendar-check me-2" style="color: #3b82f6;"></i> Data Presensi Peserta Magang</h2>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-list-check"></i></div>
                    <h5 class="card-title" style="color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">Total Presensi</h5>
                    <h2 style="color: #4b5563; font-weight: 700; margin: 0;">{{ $totalRecords }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-check-circle"></i></div>
                    <h5 class="card-title" style="color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">Hadir</h5>
                    <h2 style="color: #4b5563; font-weight: 700; margin: 0;">{{ $totalHadir }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-clock"></i></div>
                    <h5 class="card-title" style="color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">Telat</h5>
                    <h2 style="color: #4b5563; font-weight: 700; margin: 0;">{{ $totalTelat }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-envelope"></i></div>
                    <h5 class="card-title" style="color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">Izin</h5>
                    <h2 style="color: #4b5563; font-weight: 700; margin: 0;">{{ $totalIzin }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-heartbeat"></i></div>
                    <h5 class="card-title" style="color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">Sakit</h5>
                    <h2 style="color: #4b5563; font-weight: 700; margin: 0;">{{ $totalSakit }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <div style="font-size: 28px; color: #3b82f6; margin-bottom: 0.5rem;"><i class="fas fa-laptop-house"></i></div>
                    <h5 class="card-title" style="color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem;">WFH</h5>
                    <h2 style="color: #4b5563; font-weight: 700; margin: 0;">{{ $totalWfh }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4" style="border: 1px solid #e5e7eb; background: white; border-radius: 8px;">
        <div class="card-header" style="border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
            <h5 class="mb-0" style="color: #1f2937; font-weight: 600;">
                <i class="fas fa-filter me-2" style="color: #3b82f6;"></i> Filter
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" style="color: #4b5563; font-weight: 500;">Peserta Magang</label>
                    <select name="user_id" class="form-select" style="border-color: #d1d5db; border-radius: 6px;">
                        <option value="">-- Semua Peserta --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="color: #4b5563; font-weight: 500;">Bulan</label>
                    <input type="number" name="month" min="1" max="12" class="form-control"
                           value="{{ $month }}" required style="border-color: #d1d5db; border-radius: 6px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="color: #4b5563; font-weight: 500;">Tahun</label>
                    <input type="number" name="year" min="2020" class="form-control"
                           value="{{ $year }}" required style="border-color: #d1d5db; border-radius: 6px;">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn w-100" style="background-color: #3b82f6; color: white; border-radius: 6px; border: none; font-weight: 500;">
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
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        <strong>{{ $record->attendance_date->format('d/m/Y') }}</strong>
                                        <br>
                                        <small style="color: #9ca3af;">
                                            {{ $record->attendance_date->format('l') }}
                                        </small>
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        <strong>{{ $record->name }}</strong>
                                        <br>
                                        <small style="color: #9ca3af;">{{ $record->email }}</small>
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @php
                                            $statusConfig = [
                                                'hadir' => ['text' => 'Hadir', 'bg' => '#dbeafe', 'color' => '#0369a1'],
                                                'telat' => ['text' => 'Telat', 'bg' => '#fef3c7', 'color' => '#b45309'],
                                                'izin' => ['text' => 'Izin', 'bg' => '#ede9fe', 'color' => '#6d28d9'],
                                                'sakit' => ['text' => 'Sakit', 'bg' => '#fee2e2', 'color' => '#991b1b'],
                                                'wfh' => ['text' => 'WFH', 'bg' => '#cffafe', 'color' => '#0891b2']
                                            ];
                                            $config = $statusConfig[$record->status] ?? ['text' => $record->status, 'bg' => '#f3f4f6', 'color' => '#4b5563'];
                                        @endphp
                                        <span style="background: {{ $config['bg'] }}; color: {{ $config['color'] }}; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem; font-weight: 500; white-space: nowrap;">
                                            {{ $config['text'] }}
                                        </span>
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @if($record->checkin_time)
                                            <strong>{{ $record->checkin_time->format('H:i') }}</strong>
                                            <br>
                                            <small style="color: #9ca3af;">
                                                {{ $record->checkin_latitude ? '📍 ' . round($record->checkin_latitude, 4) : '-' }}
                                            </small>
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                                        @endif
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @if($record->checkout_time)
                                            <strong>{{ $record->checkout_time->format('H:i') }}</strong>
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                                        @endif
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @if($record->checkin_distance)
                                            <strong>{{ $record->checkin_distance }}m</strong>
                                            @if($record->checkin_distance <= 400)
                                                <br><small style="color: #059669;">✓ Dalam jangkauan</small>
                                            @else
                                                <br><small style="color: #b45309;">⚠ Luar jangkauan</small>
                                            @endif
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                                        @endif
                                    </td>
                                    <td style="color: #1f2937; padding: 12px; border: none;">
                                        @if($record->checkin_reason)
                                            <span style="background: #f3f4f6; color: #4b5563; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem;">
                                                {{ Str::limit($record->checkin_reason, 30) }}
                                            </span>
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav style="margin-top: 20px;">
                    <ul class="pagination justify-content-end" style="margin: 0;">
                        @if($records->onFirstPage())
                            <li class="page-item" style="opacity: 0.5;">
                                <span class="page-link" style="color: #9ca3af; border-color: #e5e7eb;">← Sebelumnya</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $records->previousPageUrl() }}&user_id={{ $userId }}" style="color: #3b82f6; border-color: #e5e7eb;">
                                    ← Sebelumnya
                                </a>
                            </li>
                        @endif

                        @foreach($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                            @if($page == $records->currentPage())
                                <li class="page-item">
                                    <span class="page-link" style="background: #3b82f6; color: white; border-color: #3b82f6;">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}&user_id={{ $userId }}" style="color: #3b82f6; border-color: #e5e7eb;">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        @if($records->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $records->nextPageUrl() }}&user_id={{ $userId }}" style="color: #3b82f6; border-color: #e5e7eb;">
                                    Selanjutnya →
                                </a>
                            </li>
                        @else
                            <li class="page-item" style="opacity: 0.5;">
                                <span class="page-link" style="color: #9ca3af; border-color: #e5e7eb;">Selanjutnya →</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @else
                <div style="background: #dbeafe; color: #0c4a6e; padding: 16px; border-radius: 6px; border: 1px solid #bfdbfe;">
                    <i class="fas fa-info-circle me-2"></i>
                    Tidak ada data presensi untuk periode ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
