<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wfh_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('week_starting');
            $table->integer('count')->default(1);
            $table->timestamps();

            // Unique index untuk track WFH per user per minggu
            $table->unique(['user_id', 'week_starting']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wfh_records');
    }
};
