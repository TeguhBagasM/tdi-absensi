@extends('layouts.app')

@section('page-title', 'Pengaturan Presensi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-cog me-2"></i> Pengaturan Sistem Presensi</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Settings Accordion -->
    <div class="row">
        <div class="col-md-8">
            <div class="accordion" id="settingsAccordion">
                <!-- Waktu Check-in -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#timeSettings">
                            <i class="fas fa-clock me-2"></i> Pengaturan Waktu
                        </button>
                    </h2>
                    <div id="timeSettings" class="accordion-collapse collapse show" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body">
                            <!-- Check-in Start Time -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sign-in-alt me-2"></i> Jam Mulai Check-in
                                </label>
                                <input type="time" id="checkin_start_time" class="form-control" value="{{ $settings['checkin_start_time'] ?? '08:00' }}">
                                <small class="text-muted">Waktu mulai karyawan dapat melakukan check-in</small>
                            </div>

                            <!-- Late After Time -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-hourglass-end me-2"></i> Batas Jam Terlambat
                                </label>
                                <input type="time" id="late_after_time" class="form-control" value="{{ $settings['late_after_time'] ?? '09:10' }}">
                                <small class="text-muted">Check-in setelah jam ini akan ditandai sebagai telat</small>
                            </div>

                            <!-- Checkout Time -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sign-out-alt me-2"></i> Jam Checkout
                                </label>
                                <input type="time" id="checkout_time" class="form-control" value="{{ $settings['checkout_time'] ?? '17:00' }}">
                                <small class="text-muted">Waktu standar karyawan checkout</small>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" onclick="saveSetting('checkin_start_time', 'time')">
                                <i class="fas fa-save me-1"></i> Simpan Pengaturan Waktu
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Geofence Settings -->
                <!-- Moved to .env configuration - office location is fixed -->


                <!-- WFH Settings -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#wfhSettings">
                            <i class="fas fa-home me-2"></i> Pengaturan Work From Home
                        </button>
                    </h2>
                    <div id="wfhSettings" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body">
                            <!-- Max WFH Per Week -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar-week me-2"></i> Maksimal WFH per Minggu
                                </label>
                                <input type="number" id="max_wfh_per_week" min="0" max="7" class="form-control" value="{{ $settings['max_wfh_per_week'] ?? '1' }}">
                                <small class="text-muted">Jumlah maksimal hari WFH yang diizinkan per minggu</small>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" onclick="saveSetting('max_wfh_per_week', 'integer')">
                                <i class="fas fa-save me-1"></i> Simpan Pengaturan WFH
                            </button>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#systemInfo">
                            <i class="fas fa-info-circle me-2"></i> Informasi Sistem
                        </button>
                    </h2>
                    <div id="systemInfo" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Total Peserta Magang:</strong></td>
                                        <td>{{ $totalUsers ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Presensi Hari Ini:</strong></td>
                                        <td>{{ $todayAttendance ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terakhir Diupdate:</strong></td>
                                        <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Sidebar -->
        <div class="col-md-4">
            <div class="card mb-3 bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-cog me-2"></i> Total Pengaturan
                    </h5>
                    <h2>{{ count($settings) }}</h2>
                    <small>Konfigurasi sistem aktif</small>
                </div>
            </div>

            <div class="card mb-3 bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-database me-2"></i> Last Sync
                    </h5>
                    <small>Pengaturan tersimpan di database</small>
                    <p class="mb-0 mt-2">
                        <i class="fas fa-check-circle me-1"></i> Connected
                    </p>
                </div>
            </div>

            <div class="card bg-warning">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb me-2"></i> Tips
                    </h5>
                    <ul class="small mb-0">
                        <li>Update pengaturan akan langsung berlaku untuk semua pengguna</li>
                        <li>Format waktu menggunakan format 24 jam (HH:mm)</li>
                        <li>Perubahan disimpan secara otomatis ke database</li>
                        <li>Max WFH per minggu: 0-7 hari</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function saveSetting(settingKey, dataType) {
        let value;

        // Get value based on type
        if (dataType === 'time') {
            value = document.getElementById(settingKey).value;
        } else if (dataType === 'integer') {
            value = document.getElementById(settingKey).value;
        } else if (dataType === 'decimal') {
            // For geofence settings, collect all three values
            if (settingKey === 'office_latitude') {
                saveGeofenceSettings();
                return;
            }
            value = document.getElementById(settingKey).value;
        } else {
            value = document.getElementById(settingKey).value;
        }

        // Show loading
        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Save to server
        fetch('{{ route('admin.attendance.settings.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                setting_key: settingKey,
                setting_value: value,
                data_type: dataType
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pengaturan telah disimpan.',
                    icon: 'success',
                    confirmButtonColor: '#0d6efd'
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
                text: 'Terjadi kesalahan saat menyimpan pengaturan.',
                icon: 'error'
            });
        });
    }
</script>
@endsection
