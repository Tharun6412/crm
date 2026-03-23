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

        // mst_cmp_tags
        DB::table('mst_cmp_tags')->insert([
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 3, 'name' => 'C'],
            ['id' => 4, 'name' => 'D'],
        ]);

        //mst_cmp_catergories
        DB::table('mst_cmp_categories')->insert([
            ['id' => '1','name' => 'Billing Related','resolution' => '3.00','resolution_type' => '1','type_id' => '1','department_id' => '1','parent_id' => NULL,'position' => '1','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:09:58','updated_at' => '2026-03-14 16:09:58'],
            ['id' => '2','name' => 'Delay in Restoration','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => NULL,'position' => '2','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:10:21','updated_at' => '2026-03-14 16:10:21'],
            ['id' => '3','name' => 'Activation Related','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '12','parent_id' => NULL,'position' => '3','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:10:46','updated_at' => '2026-03-14 16:10:46'],
            ['id' => '4','name' => 'Pressure/Quality Related','resolution' => '4.00','resolution_type' => '1','type_id' => '4','department_id' => '1','parent_id' => NULL,'position' => '4','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:11:10','updated_at' => '2026-03-14 16:11:10'],
            ['id' => '5','name' => 'Others','resolution' => '5.00','resolution_type' => '1','type_id' => '4','department_id' => '11','parent_id' => NULL,'position' => '5','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:11:33','updated_at' => '2026-03-14 16:11:33'],
            ['id' => '6','name' => 'CNG - Availability of Gas','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => NULL,'position' => '6','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:12:01','updated_at' => '2026-03-14 16:12:01'],
            ['id' => '7','name' => 'General','resolution' => '7.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => NULL,'position' => '7','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:12:29','updated_at' => '2026-03-14 16:12:29'],
            ['id' => '8','name' => 'Payment Related','resolution' => '3.00','resolution_type' => '1','type_id' => '3','department_id' => '11','parent_id' => NULL,'position' => '8','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:17:42','updated_at' => '2026-03-14 16:17:42'],
            ['id' => '9','name' => 'Alteration of Connection','resolution' => '6.00','resolution_type' => '1','type_id' => '3','department_id' => '1','parent_id' => NULL,'position' => '9','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:18:09','updated_at' => '2026-03-14 16:18:09'],
            ['id' => '10','name' => 'Temporary Disconnection & Name Change','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => NULL,'position' => '10','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:18:39','updated_at' => '2026-03-14 16:18:39'],
            ['id' => '11','name' => 'Fault/Disruption in Service','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => NULL,'position' => '11','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:19:08','updated_at' => '2026-03-14 16:19:08'],
            ['id' => '12','name' => 'CNG - Billing Related','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => NULL,'position' => '12','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:20:07','updated_at' => '2026-03-14 16:20:07'],
            ['id' => '13','name' => 'CNG - Payment Related','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => NULL,'position' => '13','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:20:29','updated_at' => '2026-03-14 16:20:29'],
            ['id' => '14','name' => 'CNG - Facility Related','resolution' => '4.00','resolution_type' => '1','type_id' => '3','department_id' => '12','parent_id' => NULL,'position' => '14','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:20:53','updated_at' => '2026-03-14 16:20:53'],
            ['id' => '15','name' => 'CNG - Service Related','resolution' => '3.00','resolution_type' => '1','type_id' => '4','department_id' => '12','parent_id' => NULL,'position' => '15','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:21:25','updated_at' => '2026-03-14 16:21:25'],
            ['id' => '16','name' => 'CNG - Pressure/Quality Related','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => NULL,'position' => '16','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:21:48','updated_at' => '2026-03-14 16:21:48'],
            ['id' => '17','name' => 'CNG - Others','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '1','parent_id' => NULL,'position' => '17','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:22:29','updated_at' => '2026-03-14 16:22:29'],
            ['id' => '18','name' => 'PNG - Non CRN','resolution' => '2.00','resolution_type' => '1','type_id' => '4','department_id' => '1','parent_id' => NULL,'position' => '18','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:23:00','updated_at' => '2026-03-14 16:23:00'],
            ['id' => '19','name' => 'Emergency','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => NULL,'position' => '19','status' => NULL,'created_by' => '2','updated_by' => NULL,'created_at' => '2026-03-14 16:23:27','updated_at' => '2026-03-14 16:23:27'],
            ['id' => '20','name' => 'Arrears in Billing','resolution' => '7.00','resolution_type' => '1','type_id' => '1','department_id' => '1','parent_id' => '1','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '21','name' => 'Billed without Gas Supply','resolution' => '7.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '1','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '22','name' => 'First Bill Not Generated','resolution' => '7.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '1','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '23','name' => 'High Billing','resolution' => '5.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '1','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '24','name' => 'Incorrect Meter Number','resolution' => '7.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '1','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '25','name' => 'Incorrect Service Charges','resolution' => '7.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '1','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '26','name' => 'Wrong Meter Reading','resolution' => '7.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '1','position' => '7','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '27','name' => 'Bill Generation','resolution' => '7.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '1','position' => '8','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '28','name' => 'Duplicate Bill','resolution' => '7.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '1','position' => '9','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '29','name' => 'Reverse Late Payment Charges','resolution' => '12.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '1','position' => '10','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '30','name' => 'FInal Bill','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '1','position' => '11','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '31','name' => 'E-Bill Registration','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '1','position' => '12','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '32','name' => 'Bill Inquiry','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '1','position' => '13','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '33','name' => 'Security Deposit','resolution' => '12.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '1','position' => '14','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '34','name' => 'Follow up Pending for Restoration','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '5','parent_id' => '2','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '35','name' => 'Restoration - Defaulter','resolution' => '14.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '2','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '36','name' => 'Restoration With Device Installed','resolution' => '14.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '2','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '37','name' => 'Restoration Without Device Installed','resolution' => '14.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '2','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '38','name' => 'Technician Visit Delayed','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '2','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '39','name' => 'Delayed/Early Connection','resolution' => '5.00','resolution_type' => '1','type_id' => '1','department_id' => '12','parent_id' => '3','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '40','name' => 'New Stove Conversion','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '3','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '41','name' => 'NG Conversion','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '12','parent_id' => '3','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '42','name' => 'Geyser installation','resolution' => '5.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '3','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '43','name' => ' Installation Enquiry','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '3','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '44','name' => 'Additional Connection Request','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '3','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '45','name' => 'Flame Problem','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '5','parent_id' => '4','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '46','name' => 'Low Line Pressure I & C','resolution' => '6.00','resolution_type' => '2','type_id' => '1','department_id' => '5','parent_id' => '4','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '47','name' => 'Pressure Fluctuation','resolution' => '1.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '4','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '48','name' => ' Payment Options','resolution' => '1.00','resolution_type' => '1','type_id' => '3','department_id' => '11','parent_id' => '5','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '49','name' => 'Dom Query - Service/ Follow Up Call','resolution' => '2.00','resolution_type' => '1','type_id' => '3','department_id' => '11','parent_id' => '5','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '50','name' => 'Query on Domestic Product','resolution' => '2.00','resolution_type' => '1','type_id' => '3','department_id' => '11','parent_id' => '5','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '51','name' => 'Service Coverage Area Inquiry','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '5','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '52','name' => 'Escalation Request / Language Change','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '5','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '53','name' => 'Permanent Disconnection','resolution' => '12.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '5','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '54','name' => 'Service Feedback','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '5','position' => '7','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '55','name' => 'office enquiry','resolution' => '8.00','resolution_type' => '2','type_id' => '3','department_id' => '11','parent_id' => '5','position' => '8','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '56','name' => 'Wrap up/ Abandoned','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '5','position' => '9','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '57','name' => 'CNG Locations','resolution' => '2.00','resolution_type' => '2','type_id' => '3','department_id' => '11','parent_id' => '6','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '58','name' => 'CNG Price','resolution' => '2.00','resolution_type' => '2','type_id' => '3','department_id' => '5','parent_id' => '6','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '59','name' => 'CNG Station Temporarily closed','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '5','parent_id' => '6','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '60','name' => 'Availability of Gas','resolution' => '1.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '6','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '61','name' => 'Limited Dispensing Hours','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '6','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '62','name' => 'NO GAS','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '6','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '63','name' => 'Job Vacancy','resolution' => '2.00','resolution_type' => '1','type_id' => '3','department_id' => '11','parent_id' => '7','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '64','name' => 'Office Enquiry','resolution' => '12.00','resolution_type' => '2','type_id' => '3','department_id' => '11','parent_id' => '7','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '65','name' => 'Career Oppurtunities','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '7','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '66','name' => 'Feedback / Suggestions','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '7','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '67','name' => 'CSR / Social Campaigns Inquiry','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '7','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '68','name' => 'Vendor / Partnership/Other Department Inquiry','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '7','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '69','name' => 'Refund Request','resolution' => '15.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '8','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '70','name' => 'Online Payment Failed','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '8','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '71','name' => 'Payment Not Reflected','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '8','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '72','name' => 'Overpayment Adjustment','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '8','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '73','name' => 'Request for Payment Receipt','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '8','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '74','name' => 'Payment Options','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '8','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '75','name' => 'Refund Not Received','resolution' => '4.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '8','position' => '7','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '76','name' => 'Remeasurement of Pipeline','resolution' => '5.00','resolution_type' => '1','type_id' => '4','department_id' => '12','parent_id' => '9','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '77','name' => 'Modification - Geyser/Extra Point','resolution' => '15.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '9','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '78','name' => 'Modification - GI','resolution' => '15.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '9','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '79','name' => 'Rubber Tube Replacement','resolution' => '3.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '9','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '80','name' => 'Modification-Upgradation of Meter I & C','resolution' => '22.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '9','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '81','name' => 'Modification- MDPE','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '9','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '82','name' => 'Modification- Dev/TF Location Change I & C','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '9','position' => '7','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '83','name' => 'Meter Repositioning','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '9','position' => '8','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '84','name' => 'Name/Address Correction','resolution' => '5.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '10','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '85','name' => 'Allotte Transfer','resolution' => '5.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '10','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '86','name' => 'Ownership Transfer','resolution' => '5.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '10','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '87','name' => 'Temporary Disconnection - Renovation','resolution' => '22.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '10','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '88','name' => 'Temporary Disconnection- Personal Reason','resolution' => '15.00','resolution_type' => '1','type_id' => '2','department_id' => '5','parent_id' => '10','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '89','name' => 'BP Master Data Correction','resolution' => '7.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '10','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '90','name' => 'Request for Reconnection After Temporary Disconnection','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '10','position' => '7','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '91','name' => 'Site Related (Malba]','resolution' => '5.00','resolution_type' => '1','type_id' => '4','department_id' => '12','parent_id' => '11','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '92','name' => 'Leakage','resolution' => '3.00','resolution_type' => '2','type_id' => '3','department_id' => '5','parent_id' => '11','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '93','name' => 'No Gas Supply','resolution' => '5.00','resolution_type' => '2','type_id' => '3','department_id' => '5','parent_id' => '11','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '94','name' => 'Improper Installation','resolution' => '12.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '11','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '95','name' => 'Frequent Disconnection','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '11','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '96','name' => 'Defective Meter','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '11','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '97','name' => 'Gas Smell/Leak Complaint','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '11','position' => '7','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '98','name' => 'Clogged Burner / Appliance','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '11','position' => '8','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '99','name' => 'Complaint on Staff Behavior','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '11','position' => '9','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '100','name' => 'Billing Related','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '5','parent_id' => '12','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '101','name' => 'Duplicate Billing','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '12','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '102','name' => 'Incorrect Billing Amount','resolution' => '24.00','resolution_type' => '2','type_id' => '1','department_id' => '11','parent_id' => '12','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '103','name' => 'Overcharged at Station','resolution' => '3.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '12','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '104','name' => 'Payment Related','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '1','parent_id' => '13','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '105','name' => 'Payment Not Reflected in App/Card','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '1','parent_id' => '13','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '106','name' => 'UPI / QR Code Not Working','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '13','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '107','name' => 'Card Payment Issues','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '13','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '108','name' => 'Payment Failure at Station','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '13','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '109','name' => 'Station Infrastructure Damaged','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '14','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '110','name' => 'CNG Station Issue','resolution' => '3.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '14','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '111','name' => 'Poor Lighting or Cleanliness','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '14','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '112','name' => 'Restroom Not Available','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '14','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '113','name' => 'No Air/Water Facility','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '14','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '114','name' => 'Service Related','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '5','parent_id' => '15','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '115','name' => 'Station Not Following Queue System','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '15','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '116','name' => 'No Staff at Station','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '15','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '117','name' => 'Staff Misbehavior','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '15','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '118','name' => 'Long Waiting Time','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '15','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '119','name' => 'Pressure / Quality related ','resolution' => '1.00','resolution_type' => '1','type_id' => '1','department_id' => '5','parent_id' => '16','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '120','name' => 'Inconsistent Flow','resolution' => '3.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '16','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '121','name' => 'Poor Gas Quality','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '16','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '122','name' => 'Low Gas Pressure','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '16','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '123','name' => 'CNG Franchise Enquiry','resolution' => '1.00','resolution_type' => '1','type_id' => '3','department_id' => '5','parent_id' => '17','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '124','name' => 'General Inquiry','resolution' => '4.00','resolution_type' => '2','type_id' => '2','department_id' => '11','parent_id' => '17','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '125','name' => 'Suggestion/Feedback Regarding CNG Services','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '17','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '126','name' => 'Smart Card Not Working','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '17','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '127','name' => 'Lost/Damaged Smart Card','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '17','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '128','name' => 'Request for New CNG Smart Card','resolution' => '3.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '17','position' => '6','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '129','name' => 'New Connection Request','resolution' => '2.00','resolution_type' => '1','type_id' => '3','department_id' => '11','parent_id' => '18','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '130','name' => 'New Connection Inquiry','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '18','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '131','name' => 'PD - Refund','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '1','parent_id' => '18','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '132','name' => 'PNG Reconnection','resolution' => '2.00','resolution_type' => '1','type_id' => '2','department_id' => '11','parent_id' => '18','position' => '4','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '133','name' => 'No Gas Supply','resolution' => '2.00','resolution_type' => '1','type_id' => '1','department_id' => '11','parent_id' => '18','position' => '5','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '134','name' => 'Fire Accident','resolution' => '3.00','resolution_type' => '2','type_id' => '4','department_id' => '5','parent_id' => '19','position' => '1','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '135','name' => 'Accident','resolution' => '3.00','resolution_type' => '2','type_id' => '4','department_id' => '5','parent_id' => '19','position' => '2','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
            ['id' => '136','name' => 'Pipeline Damaged','resolution' => '3.00','resolution_type' => '2','type_id' => '4','department_id' => '5','parent_id' => '19','position' => '3','status' => '1','created_by' => NULL,'updated_by' => NULL,'created_at' => NULL,'updated_at' => NULL],
        ]);
    }
}
 