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
        DB::table('adm_modules')->insert([
            ['id' => 1, 'position' => 1, 'status' => 1, 'icon' => 'bi-house', 'package_id' => 1, 'parent_id' => null, 'name' => 'Dashboard', 'url' => ''],
            ['id' => 2, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => null, 'name' => 'Consumers', 'url' => ''],
            ['id' => 3, 'position' => 3, 'status' => 1, 'icon' => 'bi-receipt', 'package_id' => 1, 'parent_id' => null, 'name' => 'Bills & Payments', 'url' => ''],
            ['id' => 4, 'position' => 4, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => null, 'name' => 'Refunds', 'url' => ''],
            ['id' => 5, 'position' => 5, 'status' => 1, 'icon' => 'bi-headphones', 'package_id' => 1, 'parent_id' => null, 'name' => 'Calls', 'url' => ''],
            ['id' => 6, 'position' => 6, 'status' => 1, 'icon' => 'bi-folder2-open', 'package_id' => 1, 'parent_id' => null, 'name' => 'Reports', 'url' => ''],
            ['id' => 7, 'position' => 7, 'status' => 1, 'icon' => 'bi-database', 'package_id' => 1, 'parent_id' => null, 'name' => 'Master Data', 'url' => ''],
            ['id' => 8, 'position' => 8, 'status' => 1, 'icon' => 'bi-gear', 'package_id' => 1, 'parent_id' => null, 'name' => 'Administration', 'url' => ''],

            ['id' => 9, 'position' => 1, 'status' => 1, 'icon' => 'bi-pencil-square', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Register', 'url' => ''],
                ['id' => 10, 'position' => 1, 'status' => 1, 'icon' => 'bi-house-add', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Domestic Consumer', 'url' => 'consumers/register/domestic'],
                ['id' => 11, 'position' => 2, 'status' => 1, 'icon' => 'bi-building-add', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Commercial Consumer', 'url' => 'consumers/register/commercial'],
                ['id' => 12, 'position' => 3, 'status' => 1, 'icon' => 'bi-building-gear', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Industrial Consumer', 'url' => 'consumers/register/industrial'],
            ['id' => 13, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Consumers', 'url' => ''],
                ['id' => 14, 'position' => 1, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => 13, 'name' => 'All', 'url' => 'consumers'],
                ['id' => 15, 'position' => 2, 'status' => 1, 'icon' => 'bi-person-x', 'package_id' => 1, 'parent_id' => 13, 'name' => 'TR Consumers', 'url' => 'consumers/tr'],
                ['id' => 16, 'position' => 3, 'status' => 1, 'icon' => 'bi-person-plus', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Registered', 'url' => 'consumers/registered'],
                ['id' => 17, 'position' => 4, 'status' => 1, 'icon' => 'bi-person-down', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Accepted', 'url' => 'consumers/accepted'],
                ['id' => 18, 'position' => 5, 'status' => 1, 'icon' => 'bi-person-gear', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Executed', 'url' => 'consumers/executed'],
                ['id' => 19, 'position' => 6, 'status' => 1, 'icon' => 'bi-person-gear', 'package_id' => 1, 'parent_id' => 13, 'name' => 'HSC', 'url' => 'consumers/hsc'],
                ['id' => 20, 'position' => 7, 'status' => 1, 'icon' => 'bi-person-check', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Activated', 'url' => 'consumers/activated'],
                ['id' => 21, 'position' => 8, 'status' => 1, 'icon' => 'bi-person-exclamation', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Temporary Disconnected', 'url' => 'consumers/td'],
                ['id' => 22, 'position' => 9, 'status' => 1, 'icon' => 'bi-person-dash', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Permanent Disconnected', 'url' => 'consumers/pd'],
                ['id' => 23, 'position' => 10, 'status' => 1, 'icon' => 'bi-person-slash', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Rejected', 'url' => 'consumers/rejected'],
            ['id' => 24, 'position' => 3, 'status' => 1, 'icon' => 'bi-droplet-half', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Geysers', 'url' => 'consumers/geysers'],
            ['id' => 25, 'position' => 4, 'status' => 1, 'icon' => 'bi-speedometer2', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Meter change', 'url' => 'consumers/meter-change'],
            ['id' => 26, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-plus', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Create', 'url' => ''],
            ['id' => 27, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-text', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Gas Bill', 'url' => 'bill/consumer/search'],
            ['id' => 28, 'position' => 2, 'status' => 1, 'icon' => 'bi-file-ruled', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Invoice', 'url' => 'bill/consumer/search'],
            ['id' => 29, 'position' => 3, 'status' => 1, 'icon' => 'bi-journal-plus', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Credidnote', 'url' => 'bill/invoice/search'],
            ['id' => 30, 'position' => 2, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Add Payment', 'url' => 'bill/invoice/search'],
            ['id' => 31, 'position' => 3, 'status' => 1, 'icon' => 'bi-credit-card-2-front', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Online transactions', 'url' => 'payments/transactions'],
            ['id' => 32, 'position' => 4, 'status' => 1, 'icon' => 'bi-pencil', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Corrections', 'url' => ''],
            ['id' => 33, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-x', 'package_id' => 1, 'parent_id' => 32, 'name' => 'Cancel Invoice', 'url' => 'invoice/cancel'],
            ['id' => 34, 'position' => 2, 'status' => 1, 'icon' => 'bi-file-minus', 'package_id' => 1, 'parent_id' => 32, 'name' => 'Reverse Payment', 'url' => 'payment/reverse'],
            ['id' => 35, 'position' => 1, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => 4, 'name' => 'Refund requests', 'url' => 'consumer/refunds'],
            ['id' => 36, 'position' => 1, 'status' => 1, 'icon' => 'bi-display', 'package_id' => 1, 'parent_id' => 5, 'name' => 'Dashboard', 'url' => 'calls/dashboard'],
            ['id' => 37, 'position' => 2, 'status' => 1, 'icon' => 'bi-telephone-plus', 'package_id' => 1, 'parent_id' => 5, 'name' => 'Create', 'url' => ''],
            ['id' => 38, 'position' => 1, 'status' => 1, 'icon' => 'bi-telephone-inbound', 'package_id' => 1, 'parent_id' => 37, 'name' => 'Consumer Call', 'url' => 'calls/consumer/search'],
            ['id' => 39, 'position' => 2, 'status' => 1, 'icon' => 'bi-telephone-inbound', 'package_id' => 1, 'parent_id' => 37, 'name' => 'External Call', 'url' => 'calls/external/create'],
            ['id' => 40, 'position' => 3, 'status' => 1, 'icon' => 'bi-telephone', 'package_id' => 1, 'parent_id' => 5, 'name' => 'All Calls', 'url' => 'calls'],
            ['id' => 41, 'position' => 1, 'status' => 1, 'icon' => 'bi-person-badge', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Users', 'url' => 'admin/users'],
            ['id' => 42, 'position' => 2, 'status' => 1, 'icon' => 'bi-person-lock', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Roles', 'url' => 'admin/roles'],
            ['id' => 43, 'position' => 3, 'status' => 1, 'icon' => 'bi-box-seam', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Modules', 'url' => 'admin/modules'],
            ['id' => 44, 'position' => 1, 'status' => 1, 'icon' => 'bi-compass', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Locations', 'url' => ''],
                ['id' => 45, 'position' => 1, 'status' => 1, 'icon' => 'bi-map', 'package_id' => 2, 'parent_id' => 44, 'name' => 'States', 'url' => 'master/states'],
                ['id' => 46, 'position' => 2, 'status' => 1, 'icon' => 'bi-pin-map', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Clusters', 'url' => 'master/states'],
                ['id' => 47, 'position' => 3, 'status' => 1, 'icon' => 'bi-geo-alt', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Geo Areas', 'url' => 'master/geo-areas'],
                ['id' => 48, 'position' => 4, 'status' => 1, 'icon' => 'bi-geo', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Districts', 'url' => 'master/districts'],
                ['id' => 49, 'position' => 5, 'status' => 1, 'icon' => 'bi-crosshair', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Charge Areas', 'url' => 'master/charge-areas'],
                ['id' => 50, 'position' => 6, 'status' => 1, 'icon' => 'bi-pin', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Areas', 'url' => 'master/areas'],
            ['id' => 51, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Consumer', 'url' => ''],
                ['id' => 52, 'position' => 1, 'status' => 1, 'icon' => 'bi-bookmarks', 'package_id' => 2, 'parent_id' => 51, 'name' => 'Schemes', 'url' => 'master/consumer/schemes'],
                ['id' => 53, 'position' => 2, 'status' => 1, 'icon' => 'bi-currency-rupee', 'package_id' => 2, 'parent_id' => 51, 'name' => 'Price', 'url' => 'master/consumer/prices'],
            ['id' => 54, 'position' => 3, 'status' => 1, 'icon' => 'bi-file-text', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Invoice', 'url' => ''],
                ['id' => 55, 'position' => 1, 'status' => 1, 'icon' => 'bi-tags', 'package_id' => 2, 'parent_id' => 54, 'name' => 'Invoice types', 'url' => 'master/invoice/types'],
                ['id' => 56, 'position' => 2, 'status' => 1, 'icon' => 'bi-list-task', 'package_id' => 2, 'parent_id' => 54, 'name' => 'Invoice items', 'url' => 'master/invoice/items'],
            ['id' => 57, 'position' => 4, 'status' => 1, 'icon' => 'bi-receipt', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Payments', 'url' => ''],
                ['id' => 58, 'position' => 1, 'status' => 1, 'icon' => 'bi-tags', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Types', 'url' => 'master/payment/types'],
        ]);
    }

}
