<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceSetting;

class AttendanceSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'setting_key' => 'checkin_start_time',
                'setting_value' => '08:00',
                'description' => 'Waktu mulai check-in (format H:i)',
                'data_type' => 'time',
            ],
            [
                'setting_key' => 'late_after_time',
                'setting_value' => '09:10',
                'description' => 'Batas waktu untuk dihitung telat (format H:i)',
                'data_type' => 'time',
            ],
            [
                'setting_key' => 'checkout_time',
                'setting_value' => '17:00',
                'description' => 'Waktu jam pulang standar (format H:i)',
                'data_type' => 'time',
            ],
            [
                'setting_key' => 'max_wfh_per_week',
                'setting_value' => '1',
                'description' => 'Maksimal WFH per minggu',
                'data_type' => 'integer',
            ],
        ];

        foreach ($settings as $setting) {
            AttendanceSetting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }
    }
}
