# GEOFENCE CONFIGURATION - SETUP GUIDE

## 📍 Lokasi Geofence

Geofence configuration telah dipindahkan ke file `.env` sebagai fixed configuration (bukan admin settable).

### Alasan Perubahan:
- Lokasi kantor adalah informasi permanent dan tidak boleh berubah-ubah di admin UI
- Lebih aman menyimpan di `.env` file (environment specific)
- Lebih mudah untuk production deployment

---

## 🔧 Setup Geofence

### Step 1: Edit file `.env`

Buka file `.env` di root project dan cari section `ATTENDANCE SYSTEM CONFIGURATION`:

```dotenv
# ============================================
# ATTENDANCE SYSTEM CONFIGURATION
# ============================================

# Geofence Settings (Office Location - Fixed Configuration)
OFFICE_LATITUDE=-6.9248406
OFFICE_LONGITUDE=107.6586951
GEOFENCE_RADIUS_METERS=400
```

### Step 2: Konfigurasi Lokasi Kantor

Ganti nilai dengan koordinat kantor Anda:

```dotenv
# Contoh untuk Jakarta (Pusat)
OFFICE_LATITUDE=-6.2088
OFFICE_LONGITUDE=106.8456
GEOFENCE_RADIUS_METERS=500

# Contoh untuk Bandung
OFFICE_LATITUDE=-6.9248406
OFFICE_LONGITUDE=107.6586951
GEOFENCE_RADIUS_METERS=400

# Contoh untuk Surabaya
OFFICE_LATITUDE=-7.2505
OFFICE_LONGITUDE=112.7508
GEOFENCE_RADIUS_METERS=400
```

### Step 3: Tentukan Radius Geofence

```dotenv
# Radius dalam meter
GEOFENCE_RADIUS_METERS=400  # Default: 400 meter
```

**Rekomendasi:**
- Office gedung besar: 400-600m
- Office area kompleks: 300-400m
- Single building: 100-200m

### Step 4: Clear Cache dan Restart

```bash
php artisan config:cache
php artisan cache:clear
```

---

## 🗺️ Cara Mendapatkan Koordinat

### Metode 1: Google Maps
1. Buka [Google Maps](https://maps.google.com)
2. Klik kanan pada lokasi kantor
3. Salin latitude dan longitude

### Metode 2: Koordinat Manual
- Latitude: garis lintang (North-South)
- Longitude: garis bujur (East-West)

### Metode 3: GPS Device
Gunakan GPS device untuk mendapatkan koordinat presisi

---

## ✅ Verifikasi Konfigurasi

### Check di Admin Panel:
1. Login sebagai admin
2. Navigasi ke: **Presensi → Pengaturan**
3. Buka accordion: **Informasi Sistem & Geofence**
4. Verifikasi koordinat dan radius sudah benar

### Check via Command Line:
```bash
php artisan tinker
config('attendance.office_latitude')
config('attendance.office_longitude')
config('attendance.geofence_radius_meters')
exit
```

---

## 🔍 Testing Geofence

### Test Check-in di Dalam Geofence:
1. Login sebagai peserta magang
2. Buka halaman Check-in
3. Izinkan akses lokasi
4. Tombol Check-in harus aktif (jika dalam radius)

### Test di Luar Geofence:
- Tombol Check-in akan disabled
- Pesan: "Anda berada di luar radius kantor"

---

## 📝 Environment Variables Reference

| Variable | Description | Example | Default |
|----------|-------------|---------|---------|
| `OFFICE_LATITUDE` | Latitude kantor (garis lintang) | -6.9248406 | -6.9248406 |
| `OFFICE_LONGITUDE` | Longitude kantor (garis bujur) | 107.6586951 | 107.6586951 |
| `GEOFENCE_RADIUS_METERS` | Radius geofence dalam meter | 400 | 400 |

---

## 🚀 Production Deployment

Saat deploy ke production:

1. **Update `.env` production dengan koordinat benar**
   ```bash
   OFFICE_LATITUDE=-6.2088
   OFFICE_LONGITUDE=106.8456
   GEOFENCE_RADIUS_METERS=400
   ```

2. **Clear cache**
   ```bash
   php artisan config:cache
   php artisan cache:clear
   ```

3. **Verify**
   - Test check-in dari dalam geofence
   - Verify koordinat di admin panel

---

## ⚠️ Troubleshooting

### Check-in button always disabled:
- ✅ Verify `.env` coordinates sudah benar
- ✅ Clear cache: `php artisan cache:clear`
- ✅ Check GPS accuracy di device
- ✅ Restart application

### Geofence radius terlalu luas:
- ✅ Kurangi `GEOFENCE_RADIUS_METERS`
- ✅ Contoh: dari 500m menjadi 300m

### Geofence radius terlalu sempit:
- ✅ Tambah `GEOFENCE_RADIUS_METERS`
- ✅ Contoh: dari 300m menjadi 400m

---

## 📞 Support

Untuk mengubah geofence:
1. Edit file `.env`
2. Run: `php artisan cache:clear`
3. Verify di admin panel

**Note:** Admin UI tidak lagi memiliki form untuk edit geofence untuk menjaga integritas data lokasi kantor.

---

**Last Updated:** 28 January 2026
**Status:** Production Ready
