<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobRoleSeeder extends Seeder
{
    public function run()
    {
        $jobRoles = [
            'Data Analyst',
            'Developer',
            'Multimedia',
            'Project Manager',
            'System Analyst',
        ];

        foreach ($jobRoles as $role) {
            DB::table('job_roles')->insert(['name' => $role]);
        }
    }
}
