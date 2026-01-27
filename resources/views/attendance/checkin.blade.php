@extends('layouts.app')

@section('page-title', 'Check-in Presensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Map Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i> Peta Lokasi Kantor
                    </h4>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 500px; border-radius: 8px; background: #f0f0f0;">
                        <!-- Map akan di-load di sini -->
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Lokasi Anda harus berada dalam radius <strong id="radius-display">400m</strong> dari kantor untuk dapat melakukan check-in otomatis.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check-in Status Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-clock me-2"></i> Status Hari Ini
                    </h4>
                </div>
                <div class="card-body">
                    @if($todayAttendance)
                        <div class="alert alert-info">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $todayAttendance->status === 'hadir' ? 'success' : 'warning' }}">
                                {{ ucfirst($todayAttendance->status) }}
                            </span>
                        </div>

                        @if($todayAttendance->checkin_time)
                            <div class="mb-3">
                                <small class="text-muted">Check-in:</small>
                                <div class="fw-bold">{{ $todayAttendance->checkin_time->format('H:i:s') }}</div>
                            </div>
                        @endif

                        @if($todayAttendance->checkout_time)
                            <div class="mb-3">
                                <small class="text-muted">Check-out:</small>
                                <div class="fw-bold">{{ $todayAttendance->checkout_time->format('H:i:s') }}</div>
                            </div>
                        @endif

                        @if($todayAttendance->checkin_distance)
                            <div class="mb-3">
                                <small class="text-muted">Jarak dari kantor:</small>
                                <div class="fw-bold">{{ $todayAttendance->checkin_distance }} meter</div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary">
                            Anda belum melakukan check-in hari ini.
                        </div>
                    @endif

                    <div id="status-container" class="mt-3"></div>

                    <!-- Check-in Button -->
                    <button type="button" class="btn btn-success w-100 mb-2" id="checkin-btn" onclick="performCheckin()"
                        @if($todayAttendance && $todayAttendance->checkin_time) style="display: none;" @endif>
                        <i class="fas fa-sign-in-alt me-1"></i> Check-in
                    </button>

                    <!-- Check-out Button (for hadir/telat) -->
                    <button type="button" class="btn btn-warning w-100 mb-2" id="checkout-btn" onclick="performCheckout()"
                        @if(!$todayAttendance || !$todayAttendance->checkin_time || $todayAttendance->checkout_time || !in_array($todayAttendance->status, ['hadir', 'telat'])) style="display: none;" @endif>
                        <i class="fas fa-sign-out-alt me-1"></i> Check-out
                    </button>

                    <!-- Manual Check-out Button (for WFH) -->
                    <button type="button" class="btn btn-info w-100 mb-2" id="manual-checkout-btn" onclick="performManualCheckout()"
                        @if(!$todayAttendance || !$todayAttendance->checkin_time || $todayAttendance->checkout_time || $todayAttendance->status !== 'wfh') style="display: none;" @endif>
                        <i class="fas fa-home me-1"></i> Check-out WFH
                    </button>

                    <!-- Manual Check-in Button -->
                    <button type="button" class="btn btn-secondary w-100"
                        @if($todayAttendance && $todayAttendance->checkin_time) disabled @endif
                        data-bs-toggle="modal" data-bs-target="#manualCheckinModal">
                        <i class="fas fa-edit me-1"></i> Manual Check-in
                    </button>
                </div>
            </div>

            <!-- Attendance Summary Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan Bulan Ini</h5>
                </div>
                <div class="card-body">
                    <div id="summary-container">
                        <small class="text-muted">Loading...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manual Check-in Modal -->
@include('attendance.partials.manual-checkin-modal')

<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css" />

<script>
    const OFFICE_LAT = {{ $geofence['latitude'] }};
    const OFFICE_LON = {{ $geofence['longitude'] }};
    const RADIUS = {{ $geofence['radius'] }};

    let map;
    let userMarker;
    let officeMarker;
    let circle;

    document.addEventListener('DOMContentLoaded', function() {
        initMap();

        // Show loading status for geolocation
        const statusContainer = document.getElementById('status-container');
        if (statusContainer) {
            statusContainer.innerHTML = '<div class="alert alert-info"><small>📍 Menunggu lokasi GPS...</small></div>';
        }

        updateTodayStatus();

        // Auto-update status setiap 10 detik
        setInterval(updateTodayStatus, 10000);
    });

    function initMap() {
        map = L.map('map').setView([OFFICE_LAT, OFFICE_LON], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Office marker
        officeMarker = L.marker([OFFICE_LAT, OFFICE_LON], {
            icon: L.icon({
                iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map);
        officeMarker.bindPopup('<b>Kantor</b><br/>Lokasi check-in');

        // Geofence circle
        circle = L.circle([OFFICE_LAT, OFFICE_LON], {
            color: 'blue',
            fillColor: '#1e90ff',
            fillOpacity: 0.2,
            radius: RADIUS
        }).addTo(map);

        // Get user location dengan accuracy tinggi dan timeout pendek
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                position => updateUserLocation(position),
                error => console.error('Geolocation error:', error),
                { 
                    enableHighAccuracy: true,  // Use GPS untuk akurasi maksimal
                    timeout: 8000,             // Timeout 8 detik (lebih cepat)
                    maximumAge: 3000           // Cache location selama 3 detik
                }
            );
        }
    }

    function updateUserLocation(position) {
        const { latitude, longitude } = position.coords;

        // Update/create user marker
        if (userMarker) {
            userMarker.setLatLng([latitude, longitude]);
        } else {
            userMarker = L.marker([latitude, longitude], {
                icon: L.icon({
                    iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                    shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [32, 45],
                    iconAnchor: [16, 45],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
        }

        userMarker.bindPopup(`<b>Lokasi Anda</b><br/>Lat: ${latitude.toFixed(5)}<br/>Lon: ${longitude.toFixed(5)}`);

        // Center map on user
        map.panTo([latitude, longitude]);

        // Update status display
        updateLocationStatus(latitude, longitude);
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth radius in meters
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function updateLocationStatus(userLat, userLon) {
        const distance = calculateDistance(userLat, userLon, OFFICE_LAT, OFFICE_LON);
        const isWithin = distance <= RADIUS;

        const statusHtml = `
            <div class="alert ${isWithin ? 'alert-success' : 'alert-warning'}">
                <strong>Jarak dari kantor:</strong> ${Math.round(distance)}m
                <br/>
                <strong>Status:</strong> ${isWithin ? '✓ Dalam jangkauan' : '✗ Diluar jangkauan'}
            </div>
        `;
        document.getElementById('status-container').innerHTML = statusHtml;

        // Update button state - enable/disable based on distance
        const checkinBtn = document.getElementById('checkin-btn');
        if (checkinBtn && checkinBtn.style.display !== 'none') {
            // Only disable button if out of range
            checkinBtn.disabled = !isWithin;
            checkinBtn.style.opacity = isWithin ? '1' : '0.5';
            checkinBtn.style.cursor = isWithin ? 'pointer' : 'not-allowed';
        }
    }

    function performCheckin() {
        if (!navigator.geolocation) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Browser Anda tidak mendukung geolocation'
            });
            return;
        }

        // Show loading dialog sambil get location
        Swal.fire({
            title: 'Sedang Mengambil Lokasi...',
            html: 'Mohon tunggu beberapa detik',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Get location dengan timeout 10 detik
        const locationTimeout = setTimeout(() => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Timeout!',
                text: 'Lokasi tidak terdeteksi. Pastikan GPS aktif dan izin lokasi diberikan.'
            });
        }, 10000);

        navigator.geolocation.getCurrentPosition(position => {
            clearTimeout(locationTimeout);
            Swal.close();

            const { latitude, longitude } = position.coords;
            const reason = prompt('Masukkan alasan jika diperlukan:');

            fetch('{{ route("attendance.store-checkin") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude,
                    reason: reason
                })
            })
            .then(response => {
                if (response.status === 422) {
                    return response.json().then(data => {
                        throw { type: 'validation', data };
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: data.message,
                    timer: 2000
                }).then(() => location.reload());
            })
            .catch(error => {
                let message = 'Terjadi kesalahan saat check-in';
                if (error.type === 'validation') {
                    message = error.data.message || message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: message
                });
            });
        }, error => {
            clearTimeout(locationTimeout);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error Lokasi',
                text: 'Tidak bisa mengakses lokasi: ' + error.message
            });
        }, {
            enableHighAccuracy: true,
            timeout: 8000,
            maximumAge: 0  // Don't use cached location for check-in
        });
    }

    function performCheckout() {
        Swal.fire({
            title: 'Konfirmasi Check-out',
            text: 'Apakah Anda yakin ingin check-out?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Check-out',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch('{{ route("attendance.store-checkout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: data.message,
                    timer: 2000
                }).then(() => location.reload());
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat check-out'
                });
            });
        });
    }

    function performManualCheckout() {
        Swal.fire({
            title: 'Konfirmasi Check-out WFH',
            text: 'Apakah Anda sudah selesai bekerja?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Check-out',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch('{{ route("attendance.store-manual-checkout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: data.message,
                    timer: 2000
                }).then(() => location.reload());
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat check-out WFH'
                });
            });
        });
    }

    function updateTodayStatus() {
        fetch('{{ route("attendance.today-status") }}')
            .then(response => response.json())
            .then(data => {
                const checkinBtn = document.getElementById('checkin-btn');
                const checkoutBtn = document.getElementById('checkout-btn');
                const manualCheckoutBtn = document.getElementById('manual-checkout-btn');

                if (data.attendance) {
                    // Hide check-in button if already checked in
                    if (data.has_checkin) {
                        checkinBtn.style.display = 'none';
                    } else {
                        checkinBtn.style.display = 'block';
                    }

                    // Show checkout button for hadir/telat if checked in but not checked out
                    if (data.has_checkin && !data.has_checkout) {
                        if (data.attendance.status === 'hadir' || data.attendance.status === 'telat') {
                            checkoutBtn.style.display = 'block';
                            manualCheckoutBtn.style.display = 'none';
                        } else if (data.attendance.status === 'wfh') {
                            checkoutBtn.style.display = 'none';
                            manualCheckoutBtn.style.display = 'block';
                        } else {
                            // izin/sakit - no checkout needed
                            checkoutBtn.style.display = 'none';
                            manualCheckoutBtn.style.display = 'none';
                        }
                    } else {
                        checkoutBtn.style.display = 'none';
                        manualCheckoutBtn.style.display = 'none';
                    }
                } else {
                    // No attendance today - show check-in button
                    checkinBtn.style.display = 'block';
                    checkoutBtn.style.display = 'none';
                    manualCheckoutBtn.style.display = 'none';
                }
            });
    }
</script>
@endsection
