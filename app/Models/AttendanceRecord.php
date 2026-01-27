<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_date',
        'checkin_time',
        'checkout_time',
        'checkin_latitude',
        'checkin_longitude',
        'checkin_distance',
        'status',
        'checkin_reason',
        'file_path',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'checkin_time' => 'datetime:H:i:s',
        'checkout_time' => 'datetime:H:i:s',
        'checkin_latitude' => 'decimal:8',
        'checkin_longitude' => 'decimal:8',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope untuk mendapatkan attendance hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', today());
    }

    // Scope untuk mendapatkan attendance user hari ini
    public function scopeForUserToday($query, $userId)
    {
        return $query->where('user_id', $userId)->today();
    }

    // Scope untuk attendance yang pending approval
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // Scope untuk attendance dalam range tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }
}
