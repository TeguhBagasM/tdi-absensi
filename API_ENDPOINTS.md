# API ENDPOINTS - SISTEM PRESENSI

## 📍 Base URL
```
http://localhost:8000 (development)
http://localhost/tdi-absensi/public (XAMPP)
```

---

## 👤 USER ROUTES (Peserta Magang)
*Requires: Authentication + peserta_magang role + is_approved = true*

### Check-in Management

#### 1. Get Check-in Page
```
GET /attendance/checkin
Response: HTML (check-in page dengan map)
```

#### 2. Submit Check-in (via AJAX)
```
POST /attendance/checkin
Content-Type: application/json
Headers: X-CSRF-TOKEN

Request Body:
{
  "latitude": -6.9248406,
  "longitude": 107.6586951,
  "reason": "Regular attendance" (optional)
}

Response: 200 OK
{
  "success": true,
  "message": "Check-in berhasil",
  "data": {
    "id": 1,
    "status": "hadir",
    "distance": 45,
    "checkin_time": "2026-01-27 08:30:00"
  }
}

Error: 422 Unprocessable Entity
{
  "success": false,
  "message": "Anda berada di luar radius kantor"
}
```

#### 3. Submit Check-out
```
POST /attendance/checkout
Content-Type: application/json
Headers: X-CSRF-TOKEN

Request Body: {} (empty)

Response: 200 OK
{
  "success": true,
  "message": "Check-out berhasil",
  "checkout_time": "2026-01-27 17:00:00"
}
```

---

### Manual Check-in

#### 1. Get Manual Check-in Form
```
GET /attendance/manual
Response: HTML (form atau modal)
```

#### 2. Submit Manual Check-in
```
POST /attendance/manual
Content-Type: multipart/form-data
Headers: X-CSRF-TOKEN

Request Body:
{
  "status": "wfh|izin|sakit",
  "reason": "Alasan kehadiran...",
  "file": <binary file> (required for izin/sakit)
}

Response: 200 OK
{
  "success": true,
  "message": "Check-in manual berhasil, menunggu persetujuan admin"
}

Error: 422 Unprocessable Entity
{
  "success": false,
  "message": "WFH limit sudah tercapai untuk minggu ini"
}
```

---

### Attendance History

#### 1. Get Attendance History
```
GET /attendance/history?month=1&year=2026
Response: HTML (history page dengan filter)

Query Parameters:
- month: 1-12 (optional, default: current month)
- year: YYYY (optional, default: current year)
```

#### 2. Get Today's Status (AJAX)
```
GET /attendance/today-status
Headers: X-Requested-With: XMLHttpRequest

Response: 200 OK
{
  "attendance": {
    "id": 1,
    "status": "hadir",
    "checkin_time": "2026-01-27 08:30:00",
    "checkout_time": "2026-01-27 17:00:00"
  },
  "has_checkin": true,
  "has_checkout": true
}
```

---

## 🔧 ADMIN ROUTES

### Attendance Approval

#### 1. Get Approval List
```
GET /admin/attendance/approvals?status=pending
Response: HTML (approval list page)

Query Parameters:
- status: pending|approved|rejected (optional)
- page: number (optional, default: 1)
```

#### 2. Approve Attendance (AJAX)
```
POST /admin/attendance/{record}/approve
Content-Type: application/json
Headers: X-CSRF-TOKEN

Request Body: {} (empty)

Response: 200 OK
{
  "success": true,
  "message": "Presensi telah disetujui."
}

Error: 422 Unprocessable Entity
{
  "success": false,
  "message": "Record tidak ditemukan"
}
```

#### 3. Reject Attendance (AJAX)
```
POST /admin/attendance/{record}/reject
Content-Type: application/json
Headers: X-CSRF-TOKEN

Request Body:
{
  "rejection_reason": "Alasan penolakan..."
}

Response: 200 OK
{
  "success": true,
  "message": "Presensi telah ditolak."
}

Error: 422 Unprocessable Entity
{
  "success": false,
  "message": "Rejection reason diperlukan"
}
```

#### 4. Get Pending Count (Widget)
```
GET /admin/attendance/pending-count
Response: 200 OK
{
  "count": 5
}
```

---

### Attendance Settings

#### 1. Get Settings Page
```
GET /admin/attendance/settings
Response: HTML (settings page dengan accordion)
```

#### 2. Update Setting (AJAX)
```
POST /admin/attendance/settings
Content-Type: application/json
Headers: X-CSRF-TOKEN

Request Body:
{
  "setting_key": "checkin_start_time|late_after_time|checkout_time|max_wfh_per_week|office_latitude|office_longitude|geofence_radius_meters",
  "setting_value": "08:00|1|100.123456|-6.123456",
  "data_type": "time|integer|decimal|string"
}

Response: 200 OK
{
  "success": true,
  "message": "Setting 'checkin_start_time' berhasil diupdate."
}

Error: 422 Unprocessable Entity
{
  "success": false,
  "message": "Validation error message"
}
```

#### 3. Get All Settings (JSON)
```
GET /admin/attendance/settings/get-all
Response: 200 OK
{
  "checkin_start_time": "08:00",
  "late_after_time": "09:10",
  "checkout_time": "17:00",
  "max_wfh_per_week": "1",
  "office_latitude": "-6.9248406",
  "office_longitude": "107.6586951",
  "geofence_radius_meters": "400"
}
```

---

## 🔐 AUTHENTICATION REQUIRED

All endpoints require one of:

### Option 1: Session-based (Form Login)
```
POST /login
Email: user@example.com
Password: password
→ Sets session cookie
```

### Option 2: Check Headers
```
All requests must include:
- X-CSRF-TOKEN (from <meta name="csrf-token">)
- Session cookies (automatically set on login)
```

---

## ✅ HTTP STATUS CODES

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden (no permission) |
| 404 | Not Found |
| 422 | Validation Failed |
| 500 | Server Error |

---

## 📝 ERROR RESPONSES

### Validation Error:
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "latitude": ["The latitude field is required"]
  }
}
```

### Authorization Error:
```json
{
  "message": "Unauthorized"
}
```

### Not Found Error:
```json
{
  "success": false,
  "message": "Record tidak ditemukan"
}
```

---

## 🧪 EXAMPLE REQUESTS

### Example 1: Submit Check-in
```bash
curl -X POST http://localhost:8000/attendance/checkin \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "latitude": -6.9248406,
    "longitude": 107.6586951,
    "reason": "Regular attendance"
  }'
```

### Example 2: Submit Manual Check-in (WFH)
```bash
curl -X POST http://localhost:8000/attendance/manual \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -F "status=wfh" \
  -F "reason=Sakit kepala" \
  -F "file=@/path/to/file.pdf"
```

### Example 3: Approve Attendance
```bash
curl -X POST http://localhost:8000/admin/attendance/1/approve \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{}'
```

### Example 4: Update Setting
```bash
curl -X POST http://localhost:8000/admin/attendance/settings \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "setting_key": "late_after_time",
    "setting_value": "09:15",
    "data_type": "time"
  }'
```

---

## 🔄 RESPONSE STRUCTURE

### Success Response:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { /* optional data */ }
}
```

### Error Response:
```json
{
  "success": false,
  "message": "Error message",
  "errors": { /* optional validation errors */ }
}
```

---

## 📊 DATA TYPES

### AttendanceRecord Object:
```json
{
  "id": 1,
  "user_id": 5,
  "attendance_date": "2026-01-27",
  "checkin_time": "2026-01-27 08:30:00",
  "checkout_time": "2026-01-27 17:00:00",
  "latitude": -6.9248406,
  "longitude": 107.6586951,
  "checkin_distance": 45,
  "status": "hadir",
  "checkin_reason": "Regular",
  "file_path": "attendance-files/123.pdf",
  "approval_status": "approved",
  "approved_by": 1,
  "approved_at": "2026-01-27 09:00:00",
  "created_at": "2026-01-27 08:30:00",
  "updated_at": "2026-01-27 09:00:00"
}
```

### AttendanceSetting Object:
```json
{
  "id": 1,
  "setting_key": "checkin_start_time",
  "setting_value": "08:00",
  "description": "Waktu mulai check-in",
  "data_type": "time",
  "updated_by": 1,
  "created_at": "2026-01-27 00:00:00",
  "updated_at": "2026-01-27 10:30:00"
}
```

---

## 🎯 COMMON WORKFLOWS

### Workflow 1: User Check-in Process
```
1. GET /attendance/checkin → Display form
2. Browser requests geolocation → Get coordinates
3. POST /attendance/checkin → Submit with coordinates
4. GET /attendance/today-status → Check status
5. POST /attendance/checkout → Checkout when ready
```

### Workflow 2: Manual Check-in (Izin)
```
1. GET /attendance/manual → Show form
2. POST /attendance/manual → Submit form with file
3. Record created with approval_status=pending
4. Admin reviews at GET /admin/attendance/approvals
5. Admin POST /admin/attendance/{id}/approve or reject
```

### Workflow 3: Admin Settings Update
```
1. GET /admin/attendance/settings → Display page
2. Admin fills form
3. POST /admin/attendance/settings → Update
4. Cache cleared automatically
5. New settings apply to next check-in
```

---

**API Version:** 1.0
**Last Updated:** 27 January 2026
**Status:** Production Ready
