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
        DB::table('spot_status')->insert([
            ['id' => 1, 'name' => 'Suspect', 'type' => 1, 'parent_id' => null, 'position' => 1],
            ['id' => 2, 'name' => 'Prospect', 'type' => 1, 'parent_id' => null, 'position' => 2],
            ['id' => 3, 'name' => 'Approach', 'type' => 1, 'parent_id' => null, 'position' => 3],
            ['id' => 4, 'name' => 'Negotiate', 'type' => 1, 'parent_id' => null, 'position' => 4],
            ['id' => 5, 'name' => 'Close', 'type' => 1, 'parent_id' => null, 'position' => 5],
            ['id' => 6, 'name' => 'Order', 'type' => 1, 'parent_id' => null, 'position' => 6],
            ['id' => 7, 'name' => 'Reasearch', 'type' => 1, 'par_ident' => 1, 'position' => 1],
            ['id' => 31, 'name' => 'IN Progress', 'type' => 2, 'parent_id' => null, 'position' => 1],
            ['id' => 32, 'name' => 'Close Won', 'type' => 2, 'parent_id' => null, 'position' => 2],
            ['id' => 33, 'name' => 'Close Lost', 'type' => 2, 'parent_id' => null, 'position' => 3],
            ['id' => 34, 'name' => 'Offer Requested', 'type' => 2, 'parent_id' => null, 'position' => 4],
            ['id' => 35, 'name' => 'Offer Approved', 'type' => 2, 'parent_id' => null, 'position' => 5],
            ['id' => 36, 'name' => 'Offer Rejected', 'type' => 2, 'parent_id' => null, 'position' => 6],
            ['id' => 37, 'name' => 'Hold', 'type' => 2, 'parent_id' => null, 'position' => 7],
            ['id' => 38, 'name' => 'Delete', 'type' => 2, 'parent_id' => null, 'position' => 8],
        ]);

        // Roles
        DB::table('spot_roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Cluster Head', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'GA Head', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Sales Officer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'HO Sales', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Viewer', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
