<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('attendance_date')->index();
            $table->time('checkin_time')->nullable();
            $table->time('checkout_time')->nullable();
            $table->decimal('checkin_latitude', 10, 8)->nullable();
            $table->decimal('checkin_longitude', 11, 8)->nullable();
            $table->integer('checkin_distance')->nullable(); // dalam meter
            $table->enum('status', ['hadir', 'telat', 'izin', 'sakit', 'wfh'])->index();
            $table->text('checkin_reason')->nullable(); // alasan telat/wfh/izin/sakit
            $table->string('file_path')->nullable(); // untuk upload bukti izin/sakit
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Unique index untuk mencegah double check-in
            $table->unique(['user_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
