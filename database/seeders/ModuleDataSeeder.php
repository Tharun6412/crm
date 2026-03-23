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
        /**
         * Packages
         */
        DB::table('adm_packages')->insert([
            ['id' => 1, 'name' => 'Administration'],
            ['id' => 2, 'name' => 'Master Data'],
            ['id' => 3, 'name' => 'Consumer'],
            ['id' => 4, 'name' => 'Bills & Payments'],
            ['id' => 5, 'name' => 'Complaints'],
            ['id' => 6, 'name' => 'Spot'],
            ['id' => 7, 'name' => 'Reports'],
        ]);

        /**
         * Modules
         */
        // adm_modules
        DB::table('adm_modules')->insert([
            //parent
            ['id' => '1','name' => 'Dashboard','slug' => NULL,'url' => '/','parent_id' => NULL,'package_id' => '1','icon' => 'bi-house','status' => '1','position' => '1'],
            ['id' => '2','name' => 'Consumers','slug' => NULL,'url' => '#consumer','parent_id' => NULL,'package_id' => '3','icon' => 'bi-people','status' => '1','position' => '2'],
            ['id' => '3','name' => 'Bills & Payments','slug' => NULL,'url' => '#bills','parent_id' => NULL,'package_id' => '4','icon' => 'bi-receipt','status' => '1','position' => '3'],
            ['id' => '4','name' => 'Refunds','slug' => NULL,'url' => '#refunds','parent_id' => NULL,'package_id' => '3','icon' => 'bi-cash-stack','status' => '1','position' => '4'],
            ['id' => '5','name' => 'Calls','slug' => NULL,'url' => '#calls','parent_id' => NULL,'package_id' => '5','icon' => 'bi-headphones','status' => '1','position' => '5'],
            ['id' => '6','name' => 'Reports','slug' => NULL,'url' => '#reports','parent_id' => NULL,'package_id' => '7','icon' => 'bi-folder2-open','status' => '1','position' => '6'],
            ['id' => '7','name' => 'Master Data','slug' => NULL,'url' => '#master','parent_id' => NULL,'package_id' => '2','icon' => 'bi-database','status' => '1','position' => '7'],
            ['id' => '8','name' => 'Spot','slug' => NULL,'url' => '#spot','parent_id' => NULL,'package_id' => '6','icon' => 'bi bi-file-earmark-check','status' => '1','position' => '8'],
            ['id' => '9','name' => 'Administration','slug' => NULL,'url' => '#admin','parent_id' => NULL,'package_id' => '1','icon' => 'bi-gear','status' => '1','position' => '9'],
            ['id' => '10','name' => 'Module Administration','slug' => NULL,'url' => 'admin/modules','parent_id' => NULL,'package_id' => '1','icon' => 'bi-gear','status' => '1','position' => '10'],
            ['id' => '11','name' => 'Help','slug' => NULL,'url' => '#','parent_id' => NULL,'package_id' => '1','icon' => 'bi-gear','status' => '1','position' => '11'],
            //child
            ['id' => '12','name' => 'Register','slug' => NULL,'url' => '#register','parent_id' => '2','package_id' => '3','icon' => 'bi-pencil-square','status' => '1','position' => '1'],
            ['id' => '13','name' => 'Consumers','slug' => NULL,'url' => '#consumers','parent_id' => '2','package_id' => '3','icon' => 'bi-people','status' => '1','position' => '2'],
            ['id' => '14','name' => 'Geysers','slug' => NULL,'url' => '#geysers','parent_id' => '2','package_id' => '3','icon' => 'bi-droplet-half','status' => '1','position' => '3'],
            ['id' => '15','name' => 'Meter change','slug' => NULL,'url' => 'consumers/meterChange','parent_id' => '2','package_id' => '3','icon' => 'bi-speedometer2','status' => '1','position' => '4'],
            
            ['id' => '16','name' => 'Create','slug' => NULL,'url' => '#create','parent_id' => '3','package_id' => '4','icon' => 'bi-file-plus','status' => '1','position' => '1'],
            ['id' => '17','name' => 'Add Payment','slug' => NULL,'url' => 'bill/invoice/search','parent_id' => '3','package_id' => '4','icon' => 'bi-cash-stack','status' => '1','position' => '2'],
            ['id' => '18','name' => 'Online transactions','slug' => NULL,'url' => 'payments/transactions','parent_id' => '3','package_id' => '4','icon' => 'bi-credit-card-2-front','status' => '1','position' => '3'],
            ['id' => '19','name' => 'Corrections','slug' => NULL,'url' => '#corrections','parent_id' => '3','package_id' => '4','icon' => 'bi-pencil','status' => '1','position' => '4'],
            
            ['id' => '20','name' => 'Refund requests','slug' => NULL,'url' => 'consumers/refunds','parent_id' => '4','package_id' => '3','icon' => 'bi-cash-stack','status' => '1','position' => '1'],
            
            ['id' => '21','name' => 'Dashboard','slug' => NULL,'url' => 'calls/dashboard','parent_id' => '5','package_id' => '5','icon' => 'bi-display','status' => '1','position' => '1'],
            ['id' => '22','name' => 'Create','slug' => NULL,'url' => '#create','parent_id' => '5','package_id' => '5','icon' => 'bi-telephone-plus','status' => '1','position' => '2'],
            ['id' => '23','name' => 'All Calls','slug' => NULL,'url' => 'calls','parent_id' => '5','package_id' => '5','icon' => 'bi-telephone','status' => '1','position' => '3'],

            ['id' => '24','name' => 'Consumer Onboarding','slug' => NULL,'url' => 'reports/consumer/onboarding','parent_id' => '6','package_id' => '7','icon' => 'bi-file-text','status' => '1','position' => '1'],
            ['id' => '25','name' => 'Schemes & Deposits','slug' => NULL,'url' => 'reports/consumer/sdReport','parent_id' => '6','package_id' => '7','icon' => 'bi-receipt','status' => '1','position' => '2'],
            ['id' => '26','name' => 'Consumer Ageing Report','slug' => NULL,'url' => 'reports/consumer/consumerAgeingReport','parent_id' => '6','package_id' => '7','icon' => 'bi-cash-stack','status' => '1','position' => '3'],
            ['id' => '27','name' => 'Refund Report','slug' => NULL,'url' => 'reports/consumer/refundReport','parent_id' => '6','package_id' => '7','icon' => 'bi-database','status' => '1','position' => '4'],
            ['id' => '28','name' => 'Invoice Ageing Report','slug' => NULL,'url' => 'reports/ageingReport','parent_id' => '6','package_id' => '7','icon' => 'bi-compass','status' => '1','position' => '5'],
            ['id' => '29','name' => 'Invoice Report','slug' => NULL,'url' => 'reports/invoiceReport','parent_id' => '6','package_id' => '7','icon' => 'bi-pencil-square','status' => '1','position' => '6'],
            ['id' => '30','name' => 'Payments Report','slug' => NULL,'url' => 'reports/paymentsReport','parent_id' => '6','package_id' => '7','icon' => 'bi-droplet-half','status' => '1','position' => '7'],
            ['id' => '31','name' => 'Recharge Report','slug' => NULL,'url' => 'reports/consumer/recharge','parent_id' => '6','package_id' => '7','icon' => 'bi-file-text','status' => '1','position' => '8'],
            ['id' => '32','name' => 'Employee Collection Report','slug' => NULL,'url' => 'reports/employee/collection','parent_id' => '6','package_id' => '7','icon' => 'bi-receipt','status' => '1','position' => '9'],
            
            ['id' => '33','name' => 'Locations','slug' => NULL,'url' => '#locations','parent_id' => '7','package_id' => '2','icon' => 'bi-compass','status' => '1','position' => '1'],
            ['id' => '34','name' => 'Consumer','slug' => NULL,'url' => '#consumer','parent_id' => '7','package_id' => '2','icon' => 'bi-people','status' => '1','position' => '2'],
            ['id' => '35','name' => 'Invoice','slug' => NULL,'url' => '#invoice','parent_id' => '7','package_id' => '2','icon' => 'bi-file-text','status' => '1','position' => '3'],
            ['id' => '36','name' => 'Payments','slug' => NULL,'url' => '#payments','parent_id' => '7','package_id' => '2','icon' => 'bi-receipt','status' => '1','position' => '4'],
            ['id' => '37','name' => 'Payment Types','slug' => NULL,'url' => 'master/payment/types','parent_id' => '36','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '1'],
            ['id' => '38','name' => 'Complaint','slug' => NULL,'url' => '#complaint','parent_id' => '7','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '6'],


            ['id' => '39','name' => 'Dashboard','slug' => NULL,'url' => 'spot/dashboard','parent_id' => '8','package_id' => '6','icon' => 'bi-database','status' => '1','position' => '1'],
            ['id' => '40','name' => 'Prospects','slug' => NULL,'url' => 'spot/prospects','parent_id' => '8','package_id' => '6','icon' => 'bi-pencil-square','status' => '1','position' => '2'],
            ['id' => '41','name' => 'Targets','slug' => NULL,'url' => 'spot/targets','parent_id' => '8','package_id' => '6','icon' => 'bi-file-text','status' => '1','position' => '3'],
            ['id' => '42','name' => 'Date change requests','slug' => NULL,'url' => 'spot/dateChangeRequest','parent_id' => '8','package_id' => '6','icon' => 'bi-compass','status' => '1','position' => '4'],
            ['id' => '43','name' => 'Comments','slug' => NULL,'url' => 'spot/comments','parent_id' => '8','package_id' => '6','icon' => 'bi-cash-stack','status' => '1','position' => '5'],


            ['id' => '44','name' => 'Users','slug' => NULL,'url' => 'admin/users','parent_id' => '9','package_id' => '1','icon' => 'bi-person-badge','status' => '1','position' => '1'],
            ['id' => '45','name' => 'Roles','slug' => NULL,'url' => 'admin/roles','parent_id' => '9','package_id' => '1','icon' => 'bi-person-lock','status' => '1','position' => '2'],
            ['id' => '46','name' => 'Modules','slug' => NULL,'url' => 'admin/modules','parent_id' => '9','package_id' => '1','icon' => 'bi-box-seam','status' => '1','position' => '3'],
            
            //subchild
            ['id' => '47','name' => 'Domestic Consumer','slug' => NULL,'url' => 'consumers/register/domestic','parent_id' => '12','package_id' => '3','icon' => 'bi-house-add','status' => '1','position' => '1'],
            ['id' => '48','name' => 'Commercial Consumer','slug' => NULL,'url' => 'consumers/register/commercial','parent_id' => '12','package_id' => '3','icon' => 'bi-building-add','status' => '1','position' => '2'],
            ['id' => '49','name' => 'Industrial Consumer','slug' => NULL,'url' => 'consumers/register/industrial','parent_id' => '12','package_id' => '3','icon' => 'bi-building-gear','status' => '1','position' => '3'],
            
            
            ['id' => '50','name' => 'All','slug' => NULL,'url' => 'consumers','parent_id' => '13','package_id' => '3','icon' => 'bi-people','status' => '1','position' => '1'],
            ['id' => '51','name' => 'TR Consumers','slug' => NULL,'url' => 'consumers/tr','parent_id' => '13','package_id' => '3','icon' => 'bi-person-x','status' => '1','position' => '2'],
            ['id' => '52','name' => 'Registered','slug' => NULL,'url' => 'consumers/registered','parent_id' => '13','package_id' => '3','icon' => 'bi-person-plus','status' => '1','position' => '3'],
            ['id' => '53','name' => 'Accepted','slug' => NULL,'url' => 'consumers/accepted','parent_id' => '13','package_id' => '3','icon' => 'bi-person-down','status' => '1','position' => '4'],
            ['id' => '54','name' => 'Executed','slug' => NULL,'url' => 'consumers/executed','parent_id' => '13','package_id' => '3','icon' => 'bi-person-gear','status' => '1','position' => '5'],
            ['id' => '55','name' => 'HSC','slug' => NULL,'url' => 'consumers/hsc','parent_id' => '13','package_id' => '3','icon' => 'bi-person-gear','status' => '1','position' => '6'],
            ['id' => '56','name' => 'Activated','slug' => NULL,'url' => 'consumers/activated','parent_id' => '13','package_id' => '3','icon' => 'bi-person-check','status' => '1','position' => '7'],
            ['id' => '57','name' => 'Temporary Disconnected','slug' => NULL,'url' => 'consumers/td','parent_id' => '13','package_id' => '3','icon' => 'bi-person-exclamation','status' => '1','position' => '8'],
            ['id' => '58','name' => 'Permanent Disconnected','slug' => NULL,'url' => 'consumers/pd','parent_id' => '13','package_id' => '3','icon' => 'bi-person-dash','status' => '1','position' => '9'],
            ['id' => '59','name' => 'Rejected','slug' => NULL,'url' => 'consumers/rejected','parent_id' => '13','package_id' => '3','icon' => 'bi-person-slash','status' => '1','position' => '10'],
            ['id' => '60','name' => 'Prepaid','slug' => NULL,'url' => 'consumers/prepaid','parent_id' => '13','package_id' => '3','icon' => 'bi-person-x','status' => '1','position' => '11'],


            ['id' => '61','name' => 'Gas Bill','slug' => NULL,'url' => 'bill/consumer/search','parent_id' => '16','package_id' => '4','icon' => 'bi-file-text','status' => '1','position' => '1'],
            ['id' => '62','name' => 'Invoice','slug' => NULL,'url' => 'bill/consumer/search','parent_id' => '16','package_id' => '4','icon' => 'bi-file-ruled','status' => '1','position' => '2'],
            ['id' => '63','name' => 'Credidnote','slug' => NULL,'url' => 'bill/invoice/search','parent_id' => '16','package_id' => '4','icon' => 'bi-journal-plus','status' => '1','position' => '3'],
            
            ['id' => '64','name' => 'Cancel Invoice','slug' => NULL,'url' => 'invoice/cancel','parent_id' => '19','package_id' => '4','icon' => 'bi-file-x','status' => '1','position' => '1'],
            ['id' => '65','name' => 'Reverse Payment','slug' => NULL,'url' => 'payments/reverse','parent_id' => '19','package_id' => '4','icon' => 'bi-file-minus','status' => '1','position' => '2'],


            ['id' => '66','name' => 'Consumer Call','slug' => NULL,'url' => 'calls/search','parent_id' => '22','package_id' => '5','icon' => 'bi-telephone-inbound','status' => '1','position' => '1'], 
            ['id' => '67','name' => 'External Call','slug' => NULL,'url' => 'calls/external/create','parent_id' => '22','package_id' => '5','icon' => 'bi-telephone-inbound','status' => '1','position' => '2'],


            ['id' => '68','name' => 'States','slug' => NULL,'url' => 'master/location/states','parent_id' => '33','package_id' => '2','icon' => 'bi-map','status' => '1','position' => '1'],
            ['id' => '69','name' => 'Clusters','slug' => NULL,'url' => 'master/location/clusters','parent_id' => '33','package_id' => '2','icon' => 'bi-pin-map','status' => '1','position' => '2'],
            ['id' => '70','name' => 'Geo Areas','slug' => NULL,'url' => 'master/location/geo-areas','parent_id' => '33','package_id' => '2','icon' => 'bi-geo-alt','status' => '1','position' => '3'],
            ['id' => '71','name' => 'Districts','slug' => NULL,'url' => 'master/location/districts','parent_id' => '33','package_id' => '2','icon' => 'bi-geo','status' => '1','position' => '4'],
            ['id' => '72','name' => 'Charge Areas','slug' => NULL,'url' => 'master/location/charge-areas','parent_id' => '33','package_id' => '2','icon' => 'bi-crosshair','status' => '1','position' => '5'],
            ['id' => '73','name' => 'Areas','slug' => NULL,'url' => 'master/location/areas','parent_id' => '33','package_id' => '2','icon' => 'bi-pin','status' => '1','position' => '6'],
            ['id' => '74','name' => 'Industrial Areas','slug' => NULL,'url' => 'master/location/industrial-areas','parent_id' => '33','package_id' => '2','icon' => 'bi-pin','status' => '1','position' => '7'],


            ['id' => '75','name' => 'Schemes','slug' => NULL,'url' => 'master/consumer/schemes','parent_id' => '34','package_id' => '2','icon' => 'bi-bookmarks','status' => '1','position' => '1'],
            ['id' => '76','name' => 'Price','slug' => NULL,'url' => 'master/consumer/prices','parent_id' => '34','package_id' => '2','icon' => 'bi-currency-rupee','status' => '1','position' => '2'],
            
            ['id' => '77','name' => 'Invoice types','slug' => NULL,'url' => 'master/invoice/types','parent_id' => '35','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '1'],
            ['id' => '78','name' => 'Invoice items','slug' => NULL,'url' => 'master/invoice/items','parent_id' => '35','package_id' => '2','icon' => 'bi-list-task','status' => '1','position' => '2'],

            ['id' => '79','name' => 'Categories','slug' => NULL,'url' => 'master/complaint/categories','parent_id' => '38','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '1'],
            ['id' => '80','name' => 'Contextual Data','slug' => NULL,'url' => 'master/complaint/contextual-data','parent_id' => '38','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '2'],
            ['id' => '81','name' => 'Payment Gateways','slug' => NULL,'url' => 'master/payment/paymentGateways','parent_id' => '36','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '2'],
        ]);

        // OLD
        // DB::table('adm_modules_1')->insert([
        //     ['id' => 1, 'position' => 1, 'status' => 1, 'icon' => 'bi-house', 'package_id' => 1, 'parent_id' => null, 'name' => 'Dashboard', 'url' => ''],
        //     ['id' => 2, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => null, 'name' => 'Consumers', 'url' => ''],
        //     ['id' => 3, 'position' => 3, 'status' => 1, 'icon' => 'bi-receipt', 'package_id' => 1, 'parent_id' => null, 'name' => 'Bills & Payments', 'url' => ''],
        //     ['id' => 4, 'position' => 4, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => null, 'name' => 'Refunds', 'url' => ''],
        //     ['id' => 5, 'position' => 5, 'status' => 1, 'icon' => 'bi-headphones', 'package_id' => 1, 'parent_id' => null, 'name' => 'Calls', 'url' => ''],
        //     ['id' => 6, 'position' => 6, 'status' => 1, 'icon' => 'bi-folder2-open', 'package_id' => 1, 'parent_id' => null, 'name' => 'Reports', 'url' => ''],
        //     ['id' => 7, 'position' => 7, 'status' => 1, 'icon' => 'bi-database', 'package_id' => 1, 'parent_id' => null, 'name' => 'Master Data', 'url' => ''],
        //     ['id' => 8, 'position' => 8, 'status' => 1, 'icon' => 'bi-gear', 'package_id' => 1, 'parent_id' => null, 'name' => 'Administration', 'url' => ''],

        //     ['id' => 9, 'position' => 1, 'status' => 1, 'icon' => 'bi-pencil-square', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Register', 'url' => ''],
        //         ['id' => 10, 'position' => 1, 'status' => 1, 'icon' => 'bi-house-add', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Domestic Consumer', 'url' => 'consumers/register/domestic'],
        //         ['id' => 11, 'position' => 2, 'status' => 1, 'icon' => 'bi-building-add', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Commercial Consumer', 'url' => 'consumers/register/commercial'],
        //         ['id' => 12, 'position' => 3, 'status' => 1, 'icon' => 'bi-building-gear', 'package_id' => 1, 'parent_id' => 9, 'name' => 'Industrial Consumer', 'url' => 'consumers/register/industrial'],
        //     ['id' => 13, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Consumers', 'url' => ''],
        //         ['id' => 14, 'position' => 1, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 1, 'parent_id' => 13, 'name' => 'All', 'url' => 'consumers'],
        //         ['id' => 15, 'position' => 2, 'status' => 1, 'icon' => 'bi-person-x', 'package_id' => 1, 'parent_id' => 13, 'name' => 'TR Consumers', 'url' => 'consumers/tr'],
        //         ['id' => 16, 'position' => 3, 'status' => 1, 'icon' => 'bi-person-plus', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Registered', 'url' => 'consumers/registered'],
        //         ['id' => 17, 'position' => 4, 'status' => 1, 'icon' => 'bi-person-down', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Accepted', 'url' => 'consumers/accepted'],
        //         ['id' => 18, 'position' => 5, 'status' => 1, 'icon' => 'bi-person-gear', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Executed', 'url' => 'consumers/executed'],
        //         ['id' => 19, 'position' => 6, 'status' => 1, 'icon' => 'bi-person-gear', 'package_id' => 1, 'parent_id' => 13, 'name' => 'HSC', 'url' => 'consumers/hsc'],
        //         ['id' => 20, 'position' => 7, 'status' => 1, 'icon' => 'bi-person-check', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Activated', 'url' => 'consumers/activated'],
        //         ['id' => 21, 'position' => 8, 'status' => 1, 'icon' => 'bi-person-exclamation', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Temporary Disconnected', 'url' => 'consumers/td'],
        //         ['id' => 22, 'position' => 9, 'status' => 1, 'icon' => 'bi-person-dash', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Permanent Disconnected', 'url' => 'consumers/pd'],
        //         ['id' => 23, 'position' => 10, 'status' => 1, 'icon' => 'bi-person-slash', 'package_id' => 1, 'parent_id' => 13, 'name' => 'Rejected', 'url' => 'consumers/rejected'],
        //     ['id' => 24, 'position' => 3, 'status' => 1, 'icon' => 'bi-droplet-half', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Geysers', 'url' => 'consumers/geysers'],
        //     ['id' => 25, 'position' => 4, 'status' => 1, 'icon' => 'bi-speedometer2', 'package_id' => 1, 'parent_id' => 2, 'name' => 'Meter change', 'url' => 'consumers/meter-change'],
        //     ['id' => 26, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-plus', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Create', 'url' => ''],
        //     ['id' => 27, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-text', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Gas Bill', 'url' => 'bill/consumer/search'],
        //     ['id' => 28, 'position' => 2, 'status' => 1, 'icon' => 'bi-file-ruled', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Invoice', 'url' => 'bill/consumer/search'],
        //     ['id' => 29, 'position' => 3, 'status' => 1, 'icon' => 'bi-journal-plus', 'package_id' => 1, 'parent_id' => 26, 'name' => 'Credidnote', 'url' => 'bill/invoice/search'],
        //     ['id' => 30, 'position' => 2, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Add Payment', 'url' => 'bill/invoice/search'],
        //     ['id' => 31, 'position' => 3, 'status' => 1, 'icon' => 'bi-credit-card-2-front', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Online transactions', 'url' => 'payments/transactions'],
        //     ['id' => 32, 'position' => 4, 'status' => 1, 'icon' => 'bi-pencil', 'package_id' => 1, 'parent_id' => 3, 'name' => 'Corrections', 'url' => ''],
        //     ['id' => 33, 'position' => 1, 'status' => 1, 'icon' => 'bi-file-x', 'package_id' => 1, 'parent_id' => 32, 'name' => 'Cancel Invoice', 'url' => 'invoice/cancel'],
        //     ['id' => 34, 'position' => 2, 'status' => 1, 'icon' => 'bi-file-minus', 'package_id' => 1, 'parent_id' => 32, 'name' => 'Reverse Payment', 'url' => 'payment/reverse'],
        //     ['id' => 35, 'position' => 1, 'status' => 1, 'icon' => 'bi-cash-stack', 'package_id' => 1, 'parent_id' => 4, 'name' => 'Refund requests', 'url' => 'consumer/refunds'],
        //     ['id' => 36, 'position' => 1, 'status' => 1, 'icon' => 'bi-display', 'package_id' => 1, 'parent_id' => 5, 'name' => 'Dashboard', 'url' => 'calls/dashboard'],
        //     ['id' => 37, 'position' => 2, 'status' => 1, 'icon' => 'bi-telephone-plus', 'package_id' => 1, 'parent_id' => 5, 'name' => 'Create', 'url' => ''],
        //     ['id' => 38, 'position' => 1, 'status' => 1, 'icon' => 'bi-telephone-inbound', 'package_id' => 1, 'parent_id' => 37, 'name' => 'Consumer Call', 'url' => 'calls/consumer/search'],
        //     ['id' => 39, 'position' => 2, 'status' => 1, 'icon' => 'bi-telephone-inbound', 'package_id' => 1, 'parent_id' => 37, 'name' => 'External Call', 'url' => 'calls/external/create'],
        //     ['id' => 40, 'position' => 3, 'status' => 1, 'icon' => 'bi-telephone', 'package_id' => 1, 'parent_id' => 5, 'name' => 'All Calls', 'url' => 'calls'],
        //     ['id' => 41, 'position' => 1, 'status' => 1, 'icon' => 'bi-person-badge', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Users', 'url' => 'admin/users'],
        //     ['id' => 42, 'position' => 2, 'status' => 1, 'icon' => 'bi-person-lock', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Roles', 'url' => 'admin/roles'],
        //     ['id' => 43, 'position' => 3, 'status' => 1, 'icon' => 'bi-box-seam', 'package_id' => 1, 'parent_id' => 8, 'name' => 'Modules', 'url' => 'admin/modules'],
        //     ['id' => 44, 'position' => 1, 'status' => 1, 'icon' => 'bi-compass', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Locations', 'url' => ''],
        //         ['id' => 45, 'position' => 1, 'status' => 1, 'icon' => 'bi-map', 'package_id' => 2, 'parent_id' => 44, 'name' => 'States', 'url' => 'master/location/states'],
        //         ['id' => 46, 'position' => 2, 'status' => 1, 'icon' => 'bi-pin-map', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Clusters', 'url' => 'master/location/clusters'],
        //         ['id' => 47, 'position' => 3, 'status' => 1, 'icon' => 'bi-geo-alt', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Geo Areas', 'url' => 'master/location/geo-areas'],
        //         ['id' => 48, 'position' => 4, 'status' => 1, 'icon' => 'bi-geo', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Districts', 'url' => 'master/location/districts'],
        //         ['id' => 49, 'position' => 5, 'status' => 1, 'icon' => 'bi-crosshair', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Charge Areas', 'url' => 'master/location/charge-areas'],
        //         ['id' => 50, 'position' => 6, 'status' => 1, 'icon' => 'bi-pin', 'package_id' => 2, 'parent_id' => 44, 'name' => 'Areas', 'url' => 'master/location/areas'],
        //     ['id' => 51, 'position' => 2, 'status' => 1, 'icon' => 'bi-people', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Consumer', 'url' => ''],
        //         ['id' => 52, 'position' => 1, 'status' => 1, 'icon' => 'bi-bookmarks', 'package_id' => 2, 'parent_id' => 51, 'name' => 'Schemes', 'url' => 'master/consumer/schemes'],
        //         ['id' => 53, 'position' => 2, 'status' => 1, 'icon' => 'bi-currency-rupee', 'package_id' => 2, 'parent_id' => 51, 'name' => 'Price', 'url' => 'master/consumer/prices'],
        //     ['id' => 54, 'position' => 3, 'status' => 1, 'icon' => 'bi-file-text', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Invoice', 'url' => ''],
        //         ['id' => 55, 'position' => 1, 'status' => 1, 'icon' => 'bi-tags', 'package_id' => 2, 'parent_id' => 54, 'name' => 'Invoice types', 'url' => 'master/invoice/types'],
        //         ['id' => 56, 'position' => 2, 'status' => 1, 'icon' => 'bi-list-task', 'package_id' => 2, 'parent_id' => 54, 'name' => 'Invoice items', 'url' => 'master/invoice/items'],
        //     ['id' => 57, 'position' => 4, 'status' => 1, 'icon' => 'bi-receipt', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Payments', 'url' => ''],
        //         ['id' => 58, 'position' => 1, 'status' => 1, 'icon' => 'bi-tags', 'package_id' => 2, 'parent_id' => 7, 'name' => 'Types', 'url' => 'master/payment/types'],
        // ]);

        /**
         * Module URLs
         */
        DB::table('adm_module_urls')->insert([
            ['id' => 1, 'module_id' => 1, 'name' => 'Dashboard', 'url' => ''],
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
