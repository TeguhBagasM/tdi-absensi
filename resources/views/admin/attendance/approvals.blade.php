@extends('layouts.app')

@section('page-title', 'Persetujuan Presensi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-check-circle me-2"></i> Persetujuan Presensi Peserta</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Filter dan Stats -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h3 class="card-title">{{ $pendingCount ?? 0 }}</h3>
                    <p class="card-text">Menunggu Persetujuan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h3 class="card-title">{{ $approvedCount ?? 0 }}</h3>
                    <p class="card-text">Sudah Disetujui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h3 class="card-title">{{ $rejectedCount ?? 0 }}</h3>
                    <p class="card-text">Ditolak</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <select id="status-filter" class="form-select" onchange="filterStatus()">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-list me-2"></i> Daftar Persetujuan</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="approvalsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Peserta</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Alasan</th>
                                    <th>Bukti</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr class="status-{{ $record->approval_status }}">
                                    <td>
                                        <strong>{{ $record->user->name }}</strong>
                                        <br/>
                                        <small class="text-muted">{{ $record->user->student_id ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        {{ $record->attendance_date->format('d/m/Y H:i') }}
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
                                        <small>{{ $record->checkin_reason ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($record->file_path)
                                            <a href="{{ Storage::url($record->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file me-1"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->approval_status === 'pending')
                                            <button class="btn btn-sm btn-success" onclick="approveRecord({{ $record->id }})">
                                                <i class="fas fa-check me-1"></i> Setujui
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="openRejectModal({{ $record->id }})">
                                                <i class="fas fa-times me-1"></i> Tolak
                                            </button>
                                        @elseif($record->approval_status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Disetujui
                                            </span>
                                            <br/>
                                            <small class="text-muted">
                                                Oleh: {{ $record->approvedBy->name ?? '-' }}<br/>
                                                {{ $record->approved_at?->format('d/m/Y H:i') ?? '-' }}
                                            </small>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">Tidak ada data persetujuan untuk ditampilkan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($records->hasPages())
                    <nav>
                        {{ $records->links() }}
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Presensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" name="rejection_reason" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Tolak Presensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let rejectModal = null;
    let currentRecordId = null;

    document.addEventListener('DOMContentLoaded', function() {
        rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'), {
            backdrop: 'static',
            keyboard: false
        });

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitReject();
        });
    });

    function filterStatus() {
        const status = document.getElementById('status-filter').value;
        if (status) {
            window.location.href = `{{ route('admin.attendance.approvals') }}?status=${status}`;
        } else {
            window.location.href = `{{ route('admin.attendance.approvals') }}`;
        }
    }

    function approveRecord(recordId) {
        Swal.fire({
            title: 'Setujui Presensi?',
            text: 'Presensi peserta akan disetujui.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitApprove(recordId);
            }
        });
    }

    function submitApprove(recordId) {
        fetch(`{{ url('/admin/attendance') }}/${recordId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Presensi telah disetujui.',
                    icon: 'success',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan.',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memproses permintaan.',
                icon: 'error'
            });
        });
    }

    function openRejectModal(recordId) {
        currentRecordId = recordId;
        document.getElementById('rejectForm').action = `{{ url('/admin/attendance') }}/${recordId}/reject`;
        rejectModal.show();
    }

    function submitReject() {
        const reason = document.querySelector('textarea[name="rejection_reason"]').value;

        fetch(`{{ url('/admin/attendance') }}/${currentRecordId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                rejection_reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                rejectModal.hide();
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Presensi telah ditolak.',
                    icon: 'success',
                    confirmButtonColor: '#dc3545'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan.',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memproses permintaan.',
                icon: 'error'
            });
        });
    }
</script>
@endsection
