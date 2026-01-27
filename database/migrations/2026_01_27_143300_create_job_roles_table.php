<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateJobRolesTable extends Migration
{
    public function up()
    {
        Schema::create('job_roles', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_roles');
    }
}
