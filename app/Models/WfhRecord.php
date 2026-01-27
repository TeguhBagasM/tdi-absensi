<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WfhRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'week_starting',
        'count',
    ];

    protected $casts = [
        'week_starting' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get WFH count untuk user minggu ini
     */
    public static function getWfhCountThisWeek($userId)
    {
        $weekStart = now()->startOfWeek();
        $record = static::where('user_id', $userId)
            ->where('week_starting', $weekStart->toDateString())
            ->first();

        return $record ? $record->count : 0;
    }

    /**
     * Increment WFH count untuk minggu ini
     */
    public static function incrementThisWeek($userId)
    {
        $weekStart = now()->startOfWeek();

        $record = static::where('user_id', $userId)
            ->where('week_starting', $weekStart->toDateString())
            ->first();

        if ($record) {
            // Jika record sudah ada, increment
            $record->increment('count');
            return $record;
        } else {
            // Jika belum ada, create dengan count = 1
            return static::create([
                'user_id' => $userId,
                'week_starting' => $weekStart->toDateString(),
                'count' => 1,
            ]);
        }
    }
}
