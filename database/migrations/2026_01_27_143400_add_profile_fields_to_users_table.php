<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->after('full_name');
            }

            if (!Schema::hasColumn('users', 'campus')) {
                $table->string('campus')->nullable()->after('student_id');
            }

            if (!Schema::hasColumn('users', 'division_id')) {
                $table->foreignId('division_id')->nullable()->after('campus')->constrained('divisions');
            }

            if (!Schema::hasColumn('users', 'job_role_id')) {
                $table->foreignId('job_role_id')->nullable()->after('division_id')->constrained('job_roles');
            }

            if (!Schema::hasColumn('users', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('job_role_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'job_role_id')) {
                $table->dropConstrainedForeignId('job_role_id');
            }

            if (Schema::hasColumn('users', 'division_id')) {
                $table->dropConstrainedForeignId('division_id');
            }

            $columns = array_filter([
                Schema::hasColumn('users', 'full_name') ? 'full_name' : null,
                Schema::hasColumn('users', 'student_id') ? 'student_id' : null,
                Schema::hasColumn('users', 'campus') ? 'campus' : null,
                Schema::hasColumn('users', 'is_approved') ? 'is_approved' : null,
            ]);

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
