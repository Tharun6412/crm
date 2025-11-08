<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Modules
        DB::table('spot_status')->insert([
            ['id' => 1, 'name' => '', 'parent_id' => null, 'position' => 1],
        ]);
    }
}
