<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpotDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Status
        DB::table('spt_stages')->insert([
            ['id' => 1, 'name' => 'Suspect', 'type' => 1, 'parent_id' => null, 'position' => 1, 'icon' => 'binoculars', 'color' => 'primary'],
            ['id' => 2, 'name' => 'Prospect', 'type' => 1, 'parent_id' => null, 'position' => 2, 'icon' => 'lightbulb', 'color' => 'warning'],
            ['id' => 3, 'name' => 'Approach', 'type' => 1, 'parent_id' => null, 'position' => 3, 'icon' => 'flag', 'color' => 'danger'],
            ['id' => 4, 'name' => 'Negotiate', 'type' => 1, 'parent_id' => null, 'position' => 4, 'icon' => 'coin', 'color' => 'info'],
            ['id' => 5, 'name' => 'Close', 'type' => 1, 'parent_id' => null, 'position' => 5, 'icon' => 'check2-square', 'color' => 'success'],
            ['id' => 6, 'name' => 'Order', 'type' => 1, 'parent_id' => null, 'position' => 6, 'icon' => 'rocket-takeoff', 'color' => 'secondary'],
            ['id' => 7, 'name' => 'Research', 'type' => 1, 'parent_id' => 1, 'position' => 1, 'icon' => null, 'color' => null],
            ['id' => 8, 'name' => 'Initial reach out', 'type' => 1, 'parent_id' => 1, 'position' => 2, 'icon' => null, 'color' => null],
            ['id' => 9, 'name' => 'Qualify needs', 'type' => 1, 'parent_id' => 2, 'position' => 1, 'icon' => null, 'color' => null],
            ['id' => 10, 'name' => 'Evaluate fit', 'type' => 1, 'parent_id' => 2, 'position' => 2, 'icon' => null, 'color' => null],
            ['id' => 11, 'name' => 'Technical', 'type' => 1, 'parent_id' => 3, 'position' => 1, 'icon' => null, 'color' => null],
            ['id' => 12, 'name' => 'Offer', 'type' => 1, 'parent_id' => 3, 'position' => 2, 'icon' => null, 'color' => null],
            ['id' => 13, 'name' => 'GSA', 'type' => 1, 'parent_id' => 4, 'position' => 1, 'icon' => null, 'color' => null],
            ['id' => 14, 'name' => 'Pricing', 'type' => 1, 'parent_id' => 4, 'position' => 2, 'icon' => null, 'color' => null],
            ['id' => 15, 'name' => 'Knowledge Partner', 'type' => 1, 'parent_id' => 4, 'position' => 3, 'icon' => null, 'color' => null],
            ['id' => 16, 'name' => 'Win', 'type' => 1, 'parent_id' => 5, 'position' => 1, 'icon' => null, 'color' => null],
            ['id' => 17, 'name' => 'Lose', 'type' => 1, 'parent_id' => 5, 'position' => 2, 'icon' => null, 'color' => null],
            ['id' => 18, 'name' => 'Execution', 'type' => 1, 'parent_id' => 6, 'position' => 1, 'icon' => null, 'color' => null],
            ['id' => 19, 'name' => 'Commission', 'type' => 1, 'parent_id' => 6, 'position' => 2, 'icon' => null, 'color' => null],
            ['id' => 20, 'name' => 'Closed Lost', 'type' => 2, 'parent_id' => null, 'position' => 7, 'icon' => null, 'color' => null],
            ['id' => 21, 'name' => 'Rejected', 'type' => 2, 'parent_id' => null, 'position' => 8, 'icon' => null, 'color' => null],
        ]);
        // Roles
        DB::table('spt_status')->insert([
            ['id' => 1, 'name' => 'IN Progress'],
            ['id' => 2, 'name' => 'Requested for approval'],
            ['id' => 3, 'name' => 'Approved'],
            ['id' => 4, 'name' => 'Closed Won'],
            ['id' => 5, 'name' => 'Hold'],
            ['id' => 6, 'name' => 'Cancel'],
        ]);
    }
}