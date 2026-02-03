@extends('layouts.app')

@section('page-title', 'Pengaturan Presensi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 style="color: #1f2937; font-weight: 600;"><i class="fas fa-cog me-2" style="color: #3b82f6;"></i> Pengaturan Sistem Presensi</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn" style="background: transparent; color: #3b82f6; border: 1px solid #bfdbfe; border-radius: 6px; padding: 8px 16px; font-weight: 500;">
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
                <div class="accordion-item" style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 12px; background: white;">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#timeSettings" style="background: #f9fafb; color: #1f2937; font-weight: 600; border: none;">
                            <i class="fas fa-clock me-2" style="color: #3b82f6;"></i> Pengaturan Waktu
                        </button>
                    </h2>
                    <div id="timeSettings" class="accordion-collapse collapse show" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body" style="border-top: 1px solid #e5e7eb;">
                            <!-- Check-in Start Time -->
                            <div class="mb-3">
                                <label class="form-label" style="color: #4b5563; font-weight: 500;">
                                    <i class="fas fa-sign-in-alt me-2" style="color: #3b82f6;"></i> Jam Mulai Check-in
                                </label>
                                <input type="time" id="checkin_start_time" class="form-control" value="{{ $settings['checkin_start_time'] ?? '08:00' }}" style="border-color: #d1d5db; border-radius: 6px;">
                                <small style="color: #9ca3af;">Waktu mulai karyawan dapat melakukan check-in</small>
                            </div>

                            <!-- Late After Time -->
                            <div class="mb-3">
                                <label class="form-label" style="color: #4b5563; font-weight: 500;">
                                    <i class="fas fa-hourglass-end me-2" style="color: #3b82f6;"></i> Batas Jam Terlambat
                                </label>
                                <input type="time" id="late_after_time" class="form-control" value="{{ $settings['late_after_time'] ?? '09:10' }}" style="border-color: #d1d5db; border-radius: 6px;">
                                <small style="color: #9ca3af;">Check-in setelah jam ini akan ditandai sebagai telat</small>
                            </div>

                            <!-- Checkout Time -->
                            <div class="mb-3">
                                <label class="form-label" style="color: #4b5563; font-weight: 500;">
                                    <i class="fas fa-sign-out-alt me-2" style="color: #3b82f6;"></i> Jam Checkout
                                </label>
                                <input type="time" id="checkout_time" class="form-control" value="{{ $settings['checkout_time'] ?? '17:00' }}" style="border-color: #d1d5db; border-radius: 6px;">
                                <small style="color: #9ca3af;">Waktu standar karyawan checkout</small>
                            </div>

                            <button type="button" class="btn btn-sm" onclick="saveAllTimeSettings()" style="background: #3b82f6; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 500;">
                                <i class="fas fa-save me-1"></i> Simpan Semua Pengaturan Waktu
                            </button>
                        </div>
                    </div>
                </div>

                <!-- WFH Settings -->
                <div class="accordion-item" style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 12px; background: white;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#wfhSettings" style="background: #f9fafb; color: #1f2937; font-weight: 600; border: none;">
                            <i class="fas fa-home me-2" style="color: #3b82f6;"></i> Pengaturan Work From Home
                        </button>
                    </h2>
                    <div id="wfhSettings" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body" style="border-top: 1px solid #e5e7eb;">
                            <!-- Max WFH Per Week -->
                            <div class="mb-3">
                                <label class="form-label" style="color: #4b5563; font-weight: 500;">
                                    <i class="fas fa-calendar-week me-2" style="color: #3b82f6;"></i> Maksimal WFH per Minggu
                                </label>
                                <input type="number" id="max_wfh_per_week" min="0" max="7" class="form-control" value="{{ $settings['max_wfh_per_week'] ?? '1' }}" style="border-color: #d1d5db; border-radius: 6px;">
                                <small style="color: #9ca3af;">Jumlah maksimal hari WFH yang diizinkan per minggu</small>
                            </div>

                            <button type="button" class="btn btn-sm" onclick="saveSetting('max_wfh_per_week', 'integer')" style="background: #3b82f6; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 500;">
                                <i class="fas fa-save me-1"></i> Simpan Pengaturan WFH
                            </button>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="accordion-item" style="border: 1px solid #e5e7eb; border-radius: 8px; background: white;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#systemInfo" style="background: #f9fafb; color: #1f2937; font-weight: 600; border: none;">
                            <i class="fas fa-info-circle me-2" style="color: #3b82f6;"></i> Informasi Sistem
                        </button>
                    </h2>
                    <div id="systemInfo" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                        <div class="accordion-body" style="border-top: 1px solid #e5e7eb;">
                            <div class="table-responsive">
                                <table class="table table-sm" style="margin-bottom: 0;">
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="color: #1f2937; font-weight: 600; padding: 12px;"><strong>Total Peserta Magang:</strong></td>
                                        <td style="color: #4b5563; padding: 12px;">{{ $totalUsers ?? 0 }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="color: #1f2937; font-weight: 600; padding: 12px;"><strong>Presensi Hari Ini:</strong></td>
                                        <td style="color: #4b5563; padding: 12px;">{{ $todayAttendance ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #1f2937; font-weight: 600; padding: 12px;"><strong>Terakhir Diupdate:</strong></td>
                                        <td style="color: #4b5563; padding: 12px;">{{ now()->format('d/m/Y H:i:s') }}</td>
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
            <div class="card mb-3" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #1f2937; font-weight: 600;">
                        <i class="fas fa-cog me-2" style="color: #3b82f6;"></i> Total Pengaturan
                    </h5>
                    <h2 style="color: #3b82f6; font-weight: 700;">{{ count($settings) }}</h2>
                    <small style="color: #6b7280;">Konfigurasi sistem aktif</small>
                </div>
            </div>

            <div class="card mb-3" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #1f2937; font-weight: 600;">
                        <i class="fas fa-database me-2" style="color: #3b82f6;"></i> Last Sync
                    </h5>
                    <small style="color: #6b7280;">Pengaturan tersimpan di database</small>
                    <p class="mb-0 mt-2" style="color: #3b82f6;">
                        <i class="fas fa-check-circle me-1"></i> Connected
                    </p>
                </div>
            </div>

            <div class="card" style="border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); border-radius: 8px;">
                <div class="card-body">
                    <h5 class="card-title" style="color: #1f2937; font-weight: 600;">
                        <i class="fas fa-lightbulb me-2" style="color: #3b82f6;"></i> Tips
                    </h5>
                    <ul class="small mb-0" style="color: #6b7280;">
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
    function saveAllTimeSettings() {
        const timeSettings = [
            { key: 'checkin_start_time', value: document.getElementById('checkin_start_time').value },
            { key: 'late_after_time', value: document.getElementById('late_after_time').value },
            { key: 'checkout_time', value: document.getElementById('checkout_time').value }
        ];

        // Validasi input tidak kosong
        for (let setting of timeSettings) {
            if (!setting.value) {
                Swal.fire({
                    title: 'Error!',
                    text: `Pengaturan ${setting.key} tidak boleh kosong`,
                    icon: 'error'
                });
                return;
            }
        }

        // Show loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Menyimpan 3 pengaturan waktu...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Save semua setting satu per satu
        let promises = timeSettings.map(setting => {
            return fetch('{{ route('admin.attendance.settings.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    setting_key: setting.key,
                    setting_value: setting.value,
                    data_type: 'time'
                })
            }).then(response => response.json());
        });

        Promise.all(promises)
            .then(results => {
                let allSuccess = results.every(data => data.success);

                if (allSuccess) {
                    Swal.fire({
                        title: 'Berhasil!',
                        html: `Semua pengaturan waktu telah disimpan:<br/>
                               - Jam Check-in: ${timeSettings[0].value}<br/>
                               - Jam Telat: ${timeSettings[1].value}<br/>
                               - Jam Checkout: ${timeSettings[2].value}`,
                        icon: 'success',
                        confirmButtonColor: '#0d6efd'
                    });
                } else {
                    let errors = results.filter(data => !data.success).map(data => data.message).join('<br/>');
                    Swal.fire({
                        title: 'Sebagian Gagal!',
                        html: errors,
                        icon: 'warning'
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
