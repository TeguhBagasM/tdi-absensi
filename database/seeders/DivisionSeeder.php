<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            'Big Data',
            'Produk',
            'Operasional',
            'Finance',
            'DevOps',
        ];

        foreach ($divisions as $division) {
            DB::table('divisions')->insert(['name' => $division]);
        }
    }
}
