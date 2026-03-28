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
            ['id' => '11','name' => 'Help','slug' => NULL,'url' => 'help','parent_id' => NULL,'package_id' => '1','icon' => 'bi-gear','status' => '1','position' => '11'],
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
            
            ['id' => '64','name' => 'Cancel Invoice','slug' => NULL,'url' => 'bill/invoice/cancel','parent_id' => '19','package_id' => '4','icon' => 'bi-file-x','status' => '1','position' => '1'],
            ['id' => '65','name' => 'Reverse Payment','slug' => NULL,'url' => 'payments/reversal','parent_id' => '19','package_id' => '4','icon' => 'bi-file-minus','status' => '1','position' => '2'],


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
            ['id' => '82','name' => 'Invoice Address','slug' => NULL,'url' => 'master/invoice/addresses','parent_id' => '35','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '3'],
            ['id' => '83','name' => 'Invoice Configuration','slug' => NULL,'url' => 'master/invoice/configuration','parent_id' => '35','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '4'],
            ['id' => '84','name' => 'Firm Types','slug' => NULL,'url' => 'master/consumer/firmTypes','parent_id' => '34','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '3'],
            ['id' => '85','name' => 'Price Groups','slug' => NULL,'url' => 'master/consumer/price-groups','parent_id' => '34','package_id' => '2','icon' => 'bi-tags','status' => '1','position' => '4'],
            // ['id' => '86','name' => 'Complaints Report','slug' => NULL,'url' => 'calls/reports/complaints','parent_id' => '6','package_id' => '7','icon' => 'bi-tags','status' => '1','position' => '10'],
        ]);

        /**
         * Admin Module Actions
         */
        DB::table('adm_module_actions')->insert([
            ['id' => '1','action' => 'All','module_id' => '1','slug' => 'all'],
            ['id' => '2','action' => 'All','module_id' => '47','slug' => 'all'],
            ['id' => '3','action' => 'All','module_id' => '48','slug' => 'all'],
            ['id' => '4','action' => 'All','module_id' => '49','slug' => 'all'],
            ['id' => '5','action' => 'View','module_id' => '50','slug' => 'view'],
            ['id' => '6','action' => 'Pay Deposit','module_id' => '50','slug' => 'pdpst'],
            ['id' => '7','action' => 'Pay Security Deposit','module_id' => '50','slug' => 'psdpst'],
            ['id' => '8','action' => 'Accept','module_id' => '50','slug' => 'acpt'],
            ['id' => '9','action' => 'Execute','module_id' => '50','slug' => 'exect'],
            ['id' => '10','action' => 'HSC','module_id' => '50','slug' => 'hsc'],
            ['id' => '11','action' => 'Activate','module_id' => '50','slug' => 'actvt'],
            ['id' => '12','action' => 'Convert to Prepaid','module_id' => '50','slug' => 'cnvpp'],
            ['id' => '13','action' => 'Meter Change','module_id' => '50','slug' => 'mtrchng'],
            ['id' => '14','action' => 'TD','module_id' => '50','slug' => 'td'],
            ['id' => '15','action' => 'PD','module_id' => '50','slug' => 'pd'],
            ['id' => '16','action' => 'Reconnect','module_id' => '50','slug' => 'rcnct'],
            ['id' => '17','action' => 'Initial Refund','module_id' => '50','slug' => 'refin'],
            ['id' => '18','action' => 'View','module_id' => '51','slug' => 'view'],
            ['id' => '19','action' => 'Pay Deposit','module_id' => '51','slug' => 'pdpst'],
            ['id' => '20','action' => 'View','module_id' => '52','slug' => 'view'],
            ['id' => '21','action' => 'Pay Security Deposit','module_id' => '52','slug' => 'psdpst'],
            ['id' => '22','action' => 'Accept','module_id' => '52','slug' => 'acpt'],
            ['id' => '23','action' => 'View','module_id' => '53','slug' => 'view'],
            ['id' => '24','action' => 'Pay Security Deposit','module_id' => '53','slug' => 'psdpst'],
            ['id' => '25','action' => 'Execute','module_id' => '53','slug' => 'exect'],
            ['id' => '26','action' => 'View','module_id' => '54','slug' => 'view'],
            ['id' => '27','action' => 'Pay Security Deposit','module_id' => '54','slug' => 'psdpst'],
            ['id' => '28','action' => 'HSC','module_id' => '54','slug' => 'hsc'],
            ['id' => '29','action' => 'View','module_id' => '55','slug' => 'view'],
            ['id' => '30','action' => 'Pay Security Deposit','module_id' => '55','slug' => 'psdpst'],
            ['id' => '31','action' => 'Activate','module_id' => '55','slug' => 'actvt'],
            ['id' => '32','action' => 'View','module_id' => '56','slug' => 'view'],
            ['id' => '33','action' => 'Pay Security Deposit','module_id' => '56','slug' => 'psdpst'],
            ['id' => '34','action' => 'Meter Change','module_id' => '56','slug' => 'mtrchng'],
            ['id' => '35','action' => 'Convert to Prepaid','module_id' => '56','slug' => 'cnvpp'],
            ['id' => '36','action' => 'View','module_id' => '57','slug' => 'view'],
            ['id' => '37','action' => 'PD','module_id' => '57','slug' => 'pd'],
            ['id' => '38','action' => 'ReConnect','module_id' => '57','slug' => 'rcnct'],
            ['id' => '39','action' => 'PD','module_id' => '56','slug' => 'pd'],
            ['id' => '40','action' => 'TD','module_id' => '56','slug' => 'td'],
            ['id' => '41','action' => 'View','module_id' => '58','slug' => 'view'],
            ['id' => '42','action' => 'Initiate Refund','module_id' => '58','slug' => 'refin'],
            ['id' => '43','action' => 'View','module_id' => '59','slug' => 'view'],
            ['id' => '44','action' => 'View','module_id' => '60','slug' => 'view'],
            ['id' => '45','action' => 'Pay Deposit','module_id' => '60','slug' => 'pdpst'],
            ['id' => '46','action' => 'Pay Security Deposit','module_id' => '60','slug' => 'psdpst'],
            ['id' => '47','action' => 'Send to HES','module_id' => '60','slug' => 'shes'],
            ['id' => '48','action' => 'Accept','module_id' => '60','slug' => 'acpt'],
            ['id' => '49','action' => 'Execute','module_id' => '60','slug' => 'exect'],
            ['id' => '50','action' => 'HSC','module_id' => '60','slug' => 'hsc'],
            ['id' => '51','action' => 'Activate','module_id' => '60','slug' => 'actvt'],
            ['id' => '52','action' => 'Meter Change','module_id' => '60','slug' => 'mtrchng'],
            ['id' => '53','action' => 'TD','module_id' => '60','slug' => 'td'],
            ['id' => '54','action' => 'PD','module_id' => '60','slug' => 'pd'],
            ['id' => '55','action' => 'Initiate Refund','module_id' => '60','slug' => 'refin'],
            ['id' => '56','action' => 'Reconnect','module_id' => '60','slug' => 'rcnct'],
            ['id' => '57','action' => 'View','module_id' => '15','slug' => 'view'],
            ['id' => '58','action' => 'View','module_id' => '61','slug' => 'view'],
            ['id' => '59','action' => 'Gas Bill','module_id' => '61','slug' => 'ggasb'],
            ['id' => '60','action' => 'Invoice','module_id' => '61','slug' => 'ginv'],
            ['id' => '61','action' => 'View','module_id' => '62','slug' => 'view'],
            ['id' => '62','action' => 'Gas Bill','module_id' => '62','slug' => 'ggasb'],
            ['id' => '63','action' => 'Invoice','module_id' => '62','slug' => 'ginv'],
            ['id' => '64','action' => 'View','module_id' => '63','slug' => 'view'],
            ['id' => '65','action' => 'Credit Note','module_id' => '63','slug' => 'gcrdr'],
            ['id' => '66','action' => 'View','module_id' => '17','slug' => 'view'],
            ['id' => '67','action' => 'Pay Invoice','module_id' => '17','slug' => 'payinv'],
            ['id' => '68','action' => 'Credit Note','module_id' => '17','slug' => 'gcrdr'],
            ['id' => '69','action' => 'Cancel Invoice','module_id' => '17','slug' => 'caninv'],
            ['id' => '70','action' => 'View','module_id' => '18','slug' => 'view'],
            ['id' => '71','action' => 'Edit','module_id' => '18','slug' => 'edit'],
            ['id' => '72','action' => 'View','module_id' => '64','slug' => 'view'],
            ['id' => '73','action' => 'Cancel Invoice','module_id' => '64','slug' => 'caninv'],
            ['id' => '74','action' => 'View','module_id' => '65','slug' => 'view'],
            ['id' => '75','action' => 'Payment Reversal','module_id' => '65','slug' => 'payrev'],
            ['id' => '76','action' => 'View','module_id' => '20','slug' => 'view'],
            ['id' => '77','action' => 'Process','module_id' => '20','slug' => 'prcs'],
            ['id' => '78','action' => 'Approve','module_id' => '20','slug' => 'apprv'],
            ['id' => '79','action' => 'Close','module_id' => '20','slug' => 'close'],
            ['id' => '80','action' => 'Export','module_id' => '20','slug' => 'exprt'],
            ['id' => '81','action' => 'View','module_id' => '21','slug' => 'view'],
            ['id' => '82','action' => 'View','module_id' => '66','slug' => 'view'],
            ['id' => '83','action' => 'View','module_id' => '67','slug' => 'view'],
            ['id' => '84','action' => 'View','module_id' => '23','slug' => 'view'],
            ['id' => '85','action' => 'Add','module_id' => '23','slug' => 'add'],
            ['id' => '86','action' => 'Edit','module_id' => '23','slug' => 'edit'],
            ['id' => '87','action' => 'Assign','module_id' => '23','slug' => 'asgn'],
            ['id' => '88','action' => 'InProgress','module_id' => '23','slug' => 'inprgs'],
            ['id' => '89','action' => 'Investigation','module_id' => '23','slug' => 'invstgn'],
            ['id' => '90','action' => 'Close','module_id' => '23','slug' => 'close'],
            ['id' => '91','action' => 'FeedBack','module_id' => '23','slug' => 'fedbk'],
            ['id' => '92','action' => 'Cancel','module_id' => '23','slug' => 'cncl'],
            ['id' => '93','action' => 'View','module_id' => '24','slug' => 'view'],
            ['id' => '94','action' => 'View','module_id' => '25','slug' => 'view'],
            ['id' => '95','action' => 'View','module_id' => '26','slug' => 'view'],
            ['id' => '96','action' => 'View','module_id' => '27','slug' => 'view'],
            ['id' => '97','action' => 'View','module_id' => '28','slug' => 'view'],
            ['id' => '98','action' => 'View','module_id' => '29','slug' => 'view'],
            ['id' => '99','action' => 'View','module_id' => '30','slug' => 'view'],
            ['id' => '100','action' => 'View','module_id' => '31','slug' => 'view'],
            ['id' => '101','action' => 'View','module_id' => '32','slug' => 'view'],
            ['id' => '102','action' => 'View','module_id' => '68','slug' => 'view'],
            ['id' => '103','action' => 'View','module_id' => '69','slug' => 'view'],
            ['id' => '104','action' => 'View','module_id' => '70','slug' => 'view'],
            ['id' => '105','action' => 'View','module_id' => '71','slug' => 'view'],
            ['id' => '106','action' => 'View','module_id' => '72','slug' => 'view'],
            ['id' => '107','action' => 'View','module_id' => '73','slug' => 'view'],
            ['id' => '108','action' => 'View','module_id' => '74','slug' => 'view'],
            ['id' => '109','action' => 'View','module_id' => '75','slug' => 'view'],
            ['id' => '110','action' => 'View','module_id' => '76','slug' => 'view'],
            ['id' => '111','action' => 'View','module_id' => '77','slug' => 'view'],
            ['id' => '112','action' => 'View','module_id' => '78','slug' => 'view'],
            ['id' => '113','action' => 'View','module_id' => '37','slug' => 'view'],
            ['id' => '114','action' => 'View','module_id' => '81','slug' => 'view'],
            ['id' => '115','action' => 'View','module_id' => '79','slug' => 'view'],
            ['id' => '116','action' => 'View','module_id' => '80','slug' => 'view'],
            ['id' => '117','action' => 'View','module_id' => '39','slug' => 'view'],
            ['id' => '118','action' => 'View','module_id' => '40','slug' => 'view'],
            ['id' => '119','action' => 'Add','module_id' => '40','slug' => 'add'],
            ['id' => '120','action' => 'Edit','module_id' => '40','slug' => 'edit'],
            ['id' => '121','action' => 'Manage Document','module_id' => '40','slug' => 'mngdoc'],
            ['id' => '122','action' => 'Pipeline Details','module_id' => '40','slug' => 'ppln'],
            ['id' => '123','action' => 'Date Change Request','module_id' => '40','slug' => 'dtchng'],
            ['id' => '124','action' => 'GA Approval','module_id' => '40','slug' => 'gapprv'],
            ['id' => '125','action' => 'Cancel','module_id' => '40','slug' => 'cncl'],
            ['id' => '126','action' => 'Comment','module_id' => '40','slug' => 'cmnt'],
            ['id' => '127','action' => 'Hold','module_id' => '40','slug' => 'hold'],
            ['id' => '128','action' => 'Unhold','module_id' => '40','slug' => 'unhold'],
            ['id' => '129','action' => 'Export','module_id' => '40','slug' => 'exprt'],
            ['id' => '130','action' => 'Complete Pipeline','module_id' => '40','slug' => 'cmppln'],
            ['id' => '131','action' => 'View','module_id' => '41','slug' => 'view'],
            ['id' => '132','action' => 'View','module_id' => '42','slug' => 'view'],
            ['id' => '133','action' => 'View','module_id' => '43','slug' => 'view'],
            ['id' => '134','action' => 'View','module_id' => '44','slug' => 'view'],
            ['id' => '135','action' => 'View','module_id' => '45','slug' => 'view'],
            ['id' => '136','action' => 'View','module_id' => '46','slug' => 'view'],
            ['id' => '137','action' => 'View','module_id' => '10','slug' => 'view'],
            ['id' => '138','action' => 'Export','module_id' => '50','slug' => 'exprt'],
            ['id' => '139','action' => 'Export','module_id' => '51','slug' => 'exprt'],
            ['id' => '140','action' => 'Export','module_id' => '53','slug' => 'exprt'],
            ['id' => '141','action' => 'Export','module_id' => '52','slug' => 'exprt'],
            ['id' => '142','action' => 'Export','module_id' => '54','slug' => 'exprt'],
            ['id' => '143','action' => 'Export','module_id' => '55','slug' => 'exprt'],
            ['id' => '144','action' => 'Export','module_id' => '56','slug' => 'exprt'],
            ['id' => '145','action' => 'Export','module_id' => '57','slug' => 'exprt'],
            ['id' => '146','action' => 'Export','module_id' => '58','slug' => 'exprt'],
            ['id' => '147','action' => 'Export','module_id' => '60','slug' => 'exprt'],
            ['id' => '148','action' => 'Export','module_id' => '23','slug' => 'exprt']
        ]);

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
