<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // mst cns nominee relations
        DB::table('mst_cns_nominee_relations')->insert([
            ['id' => '1', 'name' => 'Mother'],
            ['id' => '2', 'name' => 'Father'],
            ['id' => '3', 'name' => 'Husband'],
            ['id' => '4', 'name' => 'Wife'],
            ['id' => '5', 'name' => 'Son'],
            ['id' => '6', 'name' => 'Daughter'],
        ]);
    }
}
