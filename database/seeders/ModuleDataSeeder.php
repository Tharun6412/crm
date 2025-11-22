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
        // Packages
        DB::table('adm_packages')->insert([
            ['id' => 1, 'name' => 'Administration'],
            ['id' => 2, 'name' => 'Master Data'],
            ['id' => 3, 'name' => 'Consumer'],
            ['id' => 4, 'name' => 'Bills & Payments'],
            ['id' => 5, 'name' => 'Complaints'],
        ]);

        // Modules
        DB::table('adm_modules')->insert([
            ['id' => 1, 'position' => 1, 'status' => 1, 'icon' => 'bi-house', 'package_id' => 1, 'parent_id' => null, 'name' => 'Dashboard'],
            ['id' => 2, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => null, 'name' => 'Consumers'],
            ['id' => 3, 'position' => 3, 'status' => 1, 'icon' => 'bi-receipt', 'package_id' => 1, 'parent_id' => null, 'name' => 'Bills & Payments'],
            ['id' => 4, 'position' => 4, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => null, 'name' => 'Refunds'],
            ['id' => 5, 'position' => 5, 'status' => 1, 'icon' => 'bi-headphones', 'package_id' => 1, 'parent_id' => null, 'name' => 'Calls'],
            ['id' => 6, 'position' => 6, 'status' => 1, 'icon' => 'bi-folder2-open', 'package_id' => 1, 'parent_id' => null, 'name' => 'Reports'],
            ['id' => 7, 'position' => 7, 'status' => 1, 'icon' => 'bi-database', 'package_id' => 1, 'parent_id' => null, 'name' => 'Master Data'],
            ['id' => 8, 'position' => 8, 'status' => 1, 'icon' => 'bi-gear', 'package_id' => 1, 'parent_id' => null, 'name' => 'Administration'],

            ['id' => 9, 'position' => 1, 'status' => 1, 'icon' => 'bi-pencil-square', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Register'],
            ['id' => 10, 'position' => 1, 'status' => 1, 'icon' => 'bi-house-add', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Domestic Consumer'],
            ['id' => 11, 'position' => 2, 'status' => 1, 'icon' => 'bi-building-add', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Commercial Consumer'],
            ['id' => 12, 'position' => 3, 'status' => 1, 'icon' => 'bi-building-gear', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Industrial Consumer'],
            ['id' => 13, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Consumers'],
            ['id' => 14, 'position' => 1, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => 13, 'name' => 'All'],
            ['id' => 15, 'position' => 2, 'status' => 1, 'icon' => 'bi-person-x', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Temporary Registered'],
            ['id' => 16, 'position' => 3, 'status' => 1, 'icon' => 'bi-person-plus', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Registered'],
            ['id' => 17, 'position' => 4, 'status' => 1, 'icon' => 'bi-person-down', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Accepted'],
            ['id' => 18, 'position' => 5, 'status' => 1, 'icon' => 'bi-person-gear', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Executed'],
            ['id' => 19, 'position' => 6, 'status' => 1, 'icon' => 'bi-person-gear', 'package_id' => 1, 'parent_id' => 13, 'name' => 'HSC'],
            ['id' => 20, 'position' => 7, 'status' => 1, 'icon' => 'bi-person-check', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Activated'],
            ['id' => 21, 'position' => 8, 'status' => 1, 'icon' => 'bi-person-exclamation', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Temporary Disconnected'],
            ['id' => 22, 'position' => 9, 'status' => 1, 'icon' => 'bi-person-dash', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Permanent Disconnected'],
            ['id' => 23, 'position' => 10, 'status' => 1, 'icon' => 'bi-person-slash', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Rejected'],
            ['id' => 24, 'position' => 3, 'status' => 1, 'icon' => 'bi-droplet-half', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Geysers'],
            ['id' => 25, 'position' => 4, 'status' => 1, 'icon' => 'bi-speedometer2', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Meter change'],
            ['id' => 26, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-plus', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Create'],
            ['id' => 27, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-text', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Gas Bill'],
            ['id' => 28, 'position' => 2, 'status' => 1, 'icon' => 'bi-file-ruled', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Invoice'],
            ['id' => 29, 'position' => 3, 'status' => 1, 'icon' => 'bi-journal-plus', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Credidnote'],
            ['id' => 30, 'position' => 2, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Add Payment'],
            ['id' => 31, 'position' => 3, 'status' => 1, 'icon' => 'bi-credit-card-2-front', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Online transactions'],
            ['id' => 32, 'position' => 4, 'status' => 1, 'icon' => 'bi-pencil', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Corrections'],
            ['id' => 33, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-x', 'package_id' => 1, 'parent_id' => 32, 'name' => 'Cancel Invoice'],
            ['id' => 34, 'position' => 2, 'status' => 1, 'icon' => 'bi-file-minus', 'package_id' => 1, 'parent_id' => 32, 'name' => 'Reverse Payment'],
            ['id' => 35, 'position' => 1, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => 4, 'name' => 'Refund requests'],
            ['id' => 36, 'position' => 1, 'status' => 1, 'icon' => 'bi-display', 'package_id' => 1, 'parent_id' => 5, 'name' => 'Dashboard'],
            ['id' => 37, 'position' => 2, 'status' => 1, 'icon' => 'bi-telephone-plus', 'package_id' => 1, 'parent_id' => 5, 'name' => 'Create'],
            ['id' => 38, 'position' => 1, 'status' => 1, 'icon' => 'bi-telephone-inbound', 'package_id' => 1, 'parent_id' => 37, 'name' => 'Consumer Call'],
            ['id' => 39, 'position' => 2, 'status' => 1, 'icon' => 'bi-telephone-inbound', 'package_id' => 1, 'parent_id' => 37, 'name' => 'External Call'],
            ['id' => 40, 'position' => 3, 'status' => 1, 'icon' => 'bi-telephone', 'package_id' => 1, 'parent_id' => 5, 'name' => 'All Calls'],
            ['id' => 41, 'position' => 1, 'status' => 1, 'icon' => 'bi-person-badge', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Users'],
            ['id' => 42, 'position' => 2, 'status' => 1, 'icon' => 'bi-person-lock', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Roles'],
            ['id' => 43, 'position' => 3, 'status' => 1, 'icon' => 'bi-box-seam', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Modules'],
        ]);

        /**
         * App Modules
         */
         DB::table('adm_app_modules')->insert([
            ['id' => 1, 'code' => 'domr', 'name' => 'Domestic Registration'],
            ['id' => 2, 'code' => 'comr', 'name' => 'Commercial Registration'],
            ['id' => 3, 'code' => 'indr', 'name' => 'Industrial Registration'],
            ['id' => 4, 'code' => 'regp', 'name' => 'Registration Payment'],
            ['id' => 5, 'code' => 'sdp', 'name' => 'SD Payment'],
            ['id' => 6, 'code' => 'acpt', 'name' => 'Accept'],
            ['id' => 7, 'code' => 'rej', 'name' => 'Reject'],
            ['id' => 8, 'code' => 'exe', 'name' => 'Execute'],
            ['id' => 9, 'code' => 'hsc', 'name' => 'HSC'],
            ['id' => 10, 'code' => 'act', 'name' => 'Activate'],
            ['id' => 11, 'code' => 'td', 'name' => 'Temporary Disconnect'],
            ['id' => 12, 'code' => 'pd', 'name' => 'Permanent Disconnect'],
            ['id' => 13, 'code' => 'gb', 'name' => 'Generate Bill'],
            ['id' => 14, 'code' => 'bp', 'name' => 'Bill Payment'],
        ]);
    }
}
