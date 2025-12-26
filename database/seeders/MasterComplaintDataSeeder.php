<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterComplaintDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Complaintg Segments
        DB::table('mst_cmp_segments')->insert([
            ['id' => 1, 'name' => 'PNGDOM'],
            ['id' => 2, 'name' => 'PNGCOM'],
            ['id' => 3, 'name' => 'PNGIND'],
            ['id' => 4, 'name' => 'CNG'],
            ['id' => 5, 'name' => 'General'],
        ]);
        
        // Complaint media
        DB::table('mst_cmp_media')->insert([
            ['id' => 1, 'name' => 'Telephonic'],
            ['id' => 2, 'name' => 'Email'],
            ['id' => 3, 'name' => 'Consumer Mobile App'],
            ['id' => 4, 'name' => 'Consumer Web Portal'],
            ['id' => 5, 'name' => 'Web Chat'],
            ['id' => 6, 'name' => 'Social Media'],
            ['id' => 7, 'name' => 'Feedback Form'],
            ['id' => 8, 'name' => 'Others'],
        ]);

        // Complaint type
        DB::table('mst_cmp_types')->insert([
            ['id' => 1, 'name' => 'Enquiry'],
            ['id' => 2, 'name' => 'Request'],
            ['id' => 3, 'name' => 'Complaint'],
            ['id' => 4, 'name' => 'Refund'],
        ]);

        // Complaint Status
        DB::table('mst_cmp_status')->insert([
            ['id' => 1, 'name' => 'Open'],
            ['id' => 2, 'name' => 'Assign'],
            ['id' => 3, 'name' => 'In-Progress'],
            ['id' => 4, 'name' => 'Investigation'],
            ['id' => 5, 'name' => 'Close'],
            ['id' => 6, 'name' => 'Cancel'],
        ]);

        // Complaint priorities
        DB::table('mst_cmp_priorities')->insert([
            ['id' => 1, 'name' => 'Critical / Emergency (P1)'],
            ['id' => 2, 'name' => 'High (P2)'],
            ['id' => 3, 'name' => 'Medium (P3)'],
            ['id' => 4, 'name' => 'Low (P4)'],
        ]);
    }
}
 