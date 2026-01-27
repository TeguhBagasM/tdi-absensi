<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
        'data_type',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get setting value by key
     * Menggunakan cache untuk performa
     */
    public static function getValue($key, $default = null)
    {
        return Cache::remember("attendance_setting_{$key}", now()->addDay(), function () use ($key, $default) {
            $setting = static::where('setting_key', $key)->first();
            return $setting ? $setting->setting_value : $default;
        });
    }

    /**
     * Get setting as array dengan key value
     */
    public static function getAllAsArray()
    {
        return Cache::remember('attendance_settings_all', now()->addDay(), function () {
            return static::pluck('setting_value', 'setting_key')->toArray();
        });
    }

    /**
     * Clear cache ketika setting di-update
     */
    protected static function booted()
    {
        static::saved(function ($model) {
            Cache::forget("attendance_setting_{$model->setting_key}");
            Cache::forget('attendance_settings_all');
        });

        static::deleted(function ($model) {
            Cache::forget("attendance_setting_{$model->setting_key}");
            Cache::forget('attendance_settings_all');
        });
    }
}
