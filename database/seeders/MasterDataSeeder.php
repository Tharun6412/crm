<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Segments
        DB::table('adm_segments')->insert([
            ['id' => 1, 'code' => 'DPNG', 'name' => 'Domestic'],
            ['id' => 2, 'code' => 'CPNG', 'name' => 'Commercial'],
            ['id' => 3, 'code' => 'IPNG', 'name' => 'Industrial'],
            
        ]);
        
        // Clusters
        DB::table('adm_clusters')->insert([
            ['id' => 1, 'code' => 'AP&TS', 'name' => 'Andhra Pradesh & Telangana', 'description' => 'Andhra Pradesh, Telangana'],
            ['id' => 2, 'code' => 'TN', 'name' => 'Tamil Nadu', 'description' => 'Tamil Nadu'],
            ['id' => 3, 'code' => 'KA', 'name' => 'Karnataka', 'description' => 'Karnataka'],
            ['id' => 4, 'code' => 'Central', 'name' => 'MP East, Maharashtra & Odisha', 'description' => 'Maharashtra, Madhya Pradesh-East, Odisha'],
            ['id' => 5, 'code' => 'North', 'name' => 'Punjab, MP West & UP', 'description' => 'Madhya Pradesh-West, Punjab, Uttar Pradesh'],
            ['id' => 6, 'code' => 'HO', 'name' => 'Head Office', 'description' => 'Head Office'],
        ]);

        // States data
        DB::table('adm_states')->insert([
            ['id' => 1, 'name' => 'Andhra Pradesh', 'status' => 1],
            ['id' => 2, 'name' => 'Telangana', 'status' => 1],
            ['id' => 3, 'name' => 'Tamil Nadu', 'status' => 1],
            ['id' => 4, 'name' => 'Karnataka', 'status' => 1],
            ['id' => 5, 'name' => 'Odisha', 'status' => 1],
            ['id' => 6, 'name' => 'Maharashtra', 'status' => 1],
            ['id' => 7, 'name' => 'Madhya Pradesh', 'status' => 1],
            ['id' => 8, 'name' => 'Panjab', 'status' => 1],
            ['id' => 9, 'name' => 'Uttar Pradesh', 'status' => 1],
            ['id' => 10, 'name' => 'Rajasthan', 'status' => 1],
        ]);
        
        // GA
        DB::table('adm_ga')->insert([
            ['id' => 1, 'code' => '1106', 'name' => 'Krishna GA', 'state_id' => 1, 'cluster_id' => 1, 'status' => 1, 'position' => 1],
            ['id' => 2, 'code' => '1173', 'name' => 'Nalgonda GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 2],
            ['id' => 3, 'code' => '1172', 'name' => 'Rangareddy GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 3],
            ['id' => 4, 'code' => '1170', 'name' => 'Warangal GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 4],
            ['id' => 5, 'code' => '1168', 'name' => 'Khammam GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 5],
            ['id' => 6, 'code' => '1155', 'name' => 'Mahabubnagar GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 6],
            ['id' => 7, 'code' => '1146', 'name' => 'Thiruvanamalai GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 7],
            ['id' => 8, 'code' => '1149', 'name' => 'Tanjavur GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 8],
            ['id' => 9, 'code' => '1152', 'name' => 'Dindigal GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 9],
            ['id' => 10, 'code' => '1147', 'name' => 'Perambalur GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 10],
            ['id' => 11, 'code' => '1156', 'name' => 'Yadgir GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 11],
            ['id' => 12, 'code' => '1108', 'name' => 'Belgaum GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 12],
            ['id' => 13, 'code' => '1105', 'name' => 'Tumkur GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 13],
            ['id' => 14, 'code' => '1120', 'name' => 'Chikkballapur GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 14],
            ['id' => 15, 'code' => '1138', 'name' => 'Rayagada GA', 'state_id' => 5, 'cluster_id' => 4, 'status' => 1, 'position' => 15],
            ['id' => 16, 'code' => '1132', 'name' => 'Chandrapur GA', 'state_id' => 6, 'cluster_id' => 4, 'status' => 1, 'position' => 16],
            ['id' => 17, 'code' => '1127', 'name' => 'Sagar GA', 'state_id' => 7, 'cluster_id' => 4, 'status' => 1, 'position' => 17],
            ['id' => 18, 'code' => '1125', 'name' => 'Chhindwara GA', 'state_id' => 7, 'cluster_id' => 4, 'status' => 1, 'position' => 18],
            ['id' => 19, 'code' => '1126', 'name' => 'Jabalpur GA', 'state_id' => 7, 'cluster_id' => 4, 'status' => 1, 'position' => 19],
            ['id' => 20, 'code' => '1122', 'name' => 'Agar Malwa GA', 'state_id' => 7, 'cluster_id' => 5, 'status' => 1, 'position' => 20],
            ['id' => 21, 'code' => '1140', 'name' => 'Tarn Taran GA', 'state_id' => 8, 'cluster_id' => 5, 'status' => 1, 'position' => 21],
            ['id' => 22, 'code' => '1158', 'name' => 'Amroha GA', 'state_id' => 9, 'cluster_id' => 5, 'status' => 1, 'position' => 22],
            ['id' => 23, 'code' => '1159', 'name' => 'Kasganj GA', 'state_id' => 9, 'cluster_id' => 5, 'status' => 1, 'position' => 23],
            ['id' => 24, 'code' => '1123', 'name' => 'Jhalawar GA', 'state_id' => 10, 'cluster_id' => 5, 'status' => 1, 'position' => 24],
            ['id' => 25, 'code' => '0000', 'name' => 'Head Office', 'state_id' => null, 'cluster_id' => 6, 'status' => 1, 'position' => 25],
        ]);

        // Districts
        DB::table('adm_districts')->insert([
            ['code'=> '', 'name' => 'Krishna', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'NTR', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Eluru', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Belgaum', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Tumkur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Yadadri Bhuvanagir', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Warangal', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Khammam', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Medchal Malkajgiri', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Suryapet', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Nalgonda', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Vikarabad', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Rangareddy', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Bhadradri Kothagudem', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Jangaon', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'J Boopalpally', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Mahbubabad', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Hanamkonda', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Mulug', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Wardha', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Mahabubnagar', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Ariyalur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Perambalur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Sambhal', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Amroha', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Tarn Taran', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Agar Malwa', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Chhindwara', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Jabalpur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Sagar', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Neemuch', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Mandasaur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Betul', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Seoni', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Balaghat', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Damoh', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Katni', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Mandla', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Umaria', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Dindori', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Hoshangabad', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Narsinghpur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Vidisha', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Nagarkurnool', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Jhalawar', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Kasganj', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Dindigul', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Padukkottai', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Tiruvannamalai', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Villupuram', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Kallakurichi', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Sivaganga', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Thanjavur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Karur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Chikkballapur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Chandrapur', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Rayagada', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Kalahandi', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Bolangir', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Nuapada', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Jogulamma Gadwal', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Yadgiri', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Wanaparthy', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '', 'name' => 'Narayanpet', 'display_name' => '', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
        ]);

        // Departments
        DB::table('adm_departments')->insert([
            ['id' => 1, 'name' => 'Finance and Accounts (F&A)', 'status' => 1],
            ['id' => 2, 'name' => 'Stores', 'status' => 1],
            ['id' => 3, 'name' => 'Execution', 'status' => 1],
            ['id' => 4, 'name' => 'Safety', 'status' => 1],
            ['id' => 5, 'name' => 'Operations and Maintenance (O&M)', 'status' => 1],
            ['id' => 6, 'name' => 'Asset Management Group (AMG)', 'status' => 1],
            ['id' => 7, 'name' => 'QA / QC', 'status' => 1],
            ['id' => 8, 'name' => 'Human Resource (HR)', 'status' => 1],
            ['id' => 9, 'name' => 'Liaisoning', 'status' => 1],
            ['id' => 10, 'name' => 'Customer support', 'status' => 1],
            ['id' => 11, 'name' => 'Marketing', 'status' => 1],
            ['id' => 12, 'name' => 'Project Monitoring and Control (PMC)', 'status' => 1],
            ['id' => 13, 'name' => 'Instrumentation', 'status' => 1],
            ['id' => 14, 'name' => 'Information Technology (IT)', 'status' => 1],
            ['id' => 15, 'name' => 'Compliance', 'status' => 1],
            ['id' => 16, 'name' => 'Geographic Information System (GIS)', 'status' => 1],
            ['id' => 17, 'name' => 'Planning', 'status' => 1],
            ['id' => 18, 'name' => 'Administration', 'status' => 1],
        ]);

        // Roles
        DB::table('adm_roles')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'position' => 1, 'status' => 1],
            ['id' => 2, 'name' => 'Admin', 'position' => 2, 'status' => 1],
        ]);
    }
}
