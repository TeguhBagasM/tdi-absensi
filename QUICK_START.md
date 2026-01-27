# 🚀 QUICK START GUIDE - SISTEM PRESENSI (ABSENSI)

## ✅ Status: SELESAI & SIAP DIGUNAKAN

Sistem presensi lengkap dengan geofencing, approval workflow, dan dynamic settings telah selesai diimplementasikan.

---

## 📦 SETUP DATABASE

Jika belum menjalankan migrasi:

```bash
cd c:\xampp\htdocs\tdi-absensi

# Run migrations (create tables)
php artisan migrate

# Seed default settings
php artisan db:seed --class=AttendanceSettingSeeder
```

**Status Database:**
- ✅ 3 tables created: `attendance_records`, `attendance_settings`, `wfh_records`
- ✅ 7 default settings seeded

---

## 🌐 AKSES APLIKASI

### Development Server:
```bash
php artisan serve
```

Akses di: **http://localhost:8000**

### XAMPP Server:
- Ensure Apache + MySQL running di XAMPP Control Panel
- Akses di: **http://localhost/tdi-absensi/public**

---

## 👤 TEST ACCOUNTS

### Admin Account:
- Email: `admin@example.com`
- Password: (sesuai saat setup)
- Role: Admin (full access)

### User Account (Peserta Magang):
1. Register di halaman register
2. Admin approve user tersebut
3. Login dan test check-in

---

## 📋 FITUR YANG TERSEDIA

### **UNTUK PESERTA MAGANG:**

#### 1️⃣ Check-in Presensi
- Menu: **Presensi → Check-in**
- Fitur:
  - 📍 Lihat map lokasi kantor dengan Leaflet.js
  - 🗺️ Geofence radius visualization (default 400m)
  - 📡 Live GPS tracking
  - ✅ Auto check-in jika dalam radius
  - 🏠 Manual check-in (WFH/Izin/Sakit)
  - 🚪 Check-out button

#### 2️⃣ Riwayat Presensi
- Menu: **Presensi → Riwayat**
- Fitur:
  - 📅 Filter bulanan (bulan/tahun)
  - 📊 Summary stats (Hadir/Telat/Izin/Sakit/WFH)
  - 📋 Detail tabel dengan status approval
  - 📎 Download bukti file (izin/sakit)

### **UNTUK ADMIN:**

#### 1️⃣ Persetujuan Presensi
- Menu: **Persetujuan → Persetujuan Presensi**
- Fitur:
  - 📋 List pending approvals
  - 🔍 Filter by status (Pending/Approved/Rejected)
  - ✅ Approve presensi
  - ❌ Reject dengan alasan
  - 👤 Lihat info peserta
  - 📎 Review file bukti

#### 2️⃣ Pengaturan Presensi
- Menu: **Presensi → Pengaturan**
- Fitur:
  - ⏰ **Waktu:**
    - Jam mulai check-in
    - Batas jam terlambat
    - Jam checkout standar
  - 📍 **Geofence:**
    - Latitude kantor
    - Longitude kantor
    - Radius (meter)
  - 🏠 **WFH:**
    - Max WFH per minggu (default: 1)

---

## 🔑 KEY FEATURES

### ✨ Geofencing
- Menggunakan **Haversine Formula** untuk kalkulasi jarak GPS
- Default kantor: Bandung (-6.9248406, 107.6586951)
- Default radius: 400 meter
- Configurable via admin settings

### ⏱️ Late Detection
- Otomatis deteksi keterlambatan
- Default: check-in setelah 09:10 = TELAT
- Configurable via admin settings

### 🏠 WFH Management
- Max 1x per minggu (default)
- Counter reset per minggu
- Validasi otomatis saat submit

### 📄 File Upload
- Support: PDF, JPG, PNG
- Max: 5MB per file
- Required untuk izin & sakit
- Stored di: `storage/app/public/attendance-files`

### 🔄 Approval Workflow
- User submit → Pending → Admin review → Approved/Rejected
- Automatic approval untuk status hadir/telat
- Manual approval untuk izin/sakit/wfh

### 💾 Dynamic Settings
- Semua setting tersimpan di database
- Auto-caching untuk performa
- Real-time update tanpa restart

---

## 📲 WORKFLOW CHECKIN

### **Automatic Check-in (Hadir/Telat):**
```
1. User buka Check-in page
2. Izinkan GPS access
3. Sistem validasi geofence
4. Klik "Check-in"
5. Automatic create record dengan status hadir/telat
```

### **Manual Check-in (WFH/Izin/Sakit):**
```
1. User klik "Manual Check-in"
2. Pilih status (WFH/Izin/Sakit)
3. Input alasan
4. Upload file bukti (untuk izin/sakit)
5. Submit → Create pending record
6. Admin review & approve/reject
```

### **Check-out:**
```
1. User klik "Check-out"
2. Record checkout time
3. Update attendance record
```

---

## 📊 DATABASE INFO

### Tables:
- `attendance_records` - Presensi harian
- `attendance_settings` - Konfigurasi sistem
- `wfh_records` - Tracking WFH mingguan

### Unique Constraint:
- `attendance_records` (user_id, attendance_date)
- Prevent duplikat check-in per hari

### Indexes:
- `user_id` - Query peserta
- `attendance_date` - Query per tanggal
- `approval_status` - Filter pending

---

## 🛠️ TROUBLESHOOTING

### GPS tidak tracking:
- ✅ Browser harus HTTPS (localhost:8000 OK untuk local dev)
- ✅ Izinkan akses location di browser
- ✅ Device harus punya GPS

### Check-in button disabled:
- ✅ Posisi diluar radius geofence
- ✅ Check koordinat kantor di admin settings
- ✅ Periksa GPS accuracy

### Manual check-in gagal:
- ✅ File harus sesuai format (PDF/JPG/PNG)
- ✅ File size max 5MB
- ✅ WFH limit sudah tercapai untuk minggu ini?

### Settings tidak terupdate:
- ✅ Clear cache: `php artisan cache:clear`
- ✅ Reload browser (F5 atau Ctrl+Shift+R)
- ✅ Check database untuk verify update

---

## 🔒 SECURITY

✅ CSRF Protection enabled
✅ Role-based authorization
✅ Geofence validation (prevent spoofing)
✅ File validation (mimetypes, size)
✅ Input validation & sanitization
✅ SQL injection prevention (Eloquent ORM)

---

## 📚 FILE REFERENCES

| File | Purpose |
|------|---------|
| `app/Services/AttendanceService.php` | Business logic utama |
| `app/Services/GeofencingService.php` | Kalkulasi GPS |
| `app/Models/AttendanceRecord.php` | Model presensi |
| `app/Models/AttendanceSetting.php` | Model setting |
| `resources/views/attendance/checkin.blade.php` | Halaman check-in |
| `resources/views/attendance/history.blade.php` | Halaman riwayat |
| `resources/views/admin/attendance/approvals.blade.php` | Approval admin |
| `resources/views/admin/attendance/settings.blade.php` | Settings admin |
| `config/attendance.php` | Konfigurasi sistem |
| `DOKUMENTASI_ATTENDANCE_SYSTEM.md` | Dokumentasi lengkap |

---

## 🎯 NEXT STEPS

1. **Test Check-in:**
   - Login sebagai peserta
   - Buka Check-in page
   - Test geolocation

2. **Test Admin Features:**
   - Login sebagai admin
   - Review pending approvals
   - Update settings

3. **Customize Settings:**
   - Adjust office coordinates
   - Change geofence radius
   - Update time settings

4. **Deploy to Production:**
   - Set `.env` variables untuk office location
   - Configure file storage
   - Setup SSL/HTTPS
   - Backup database regularly

---

**Last Updated:** 27 Jan 2026
**Status:** Production Ready ✅
**Version:** 1.0

Untuk pertanyaan atau issue, cek `DOKUMENTASI_ATTENDANCE_SYSTEM.md` untuk dokumentasi lengkap.
