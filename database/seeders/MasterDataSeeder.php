<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Segments
        DB::table('mst_segments')->insert([
            ['id' => 1, 'code' => 'DPNG', 'name' => 'Domestic'],
            ['id' => 2, 'code' => 'CPNG', 'name' => 'Commercial'],
            ['id' => 3, 'code' => 'IPNG', 'name' => 'Industrial'],
        ]);
        
        // Clusters
        DB::table('mst_clusters')->insert([
            ['id' => 1, 'code' => 'AP&TS', 'name' => 'Andhra Pradesh & Telangana', 'description' => 'Andhra Pradesh, Telangana'],
            ['id' => 2, 'code' => 'TN', 'name' => 'Tamil Nadu', 'description' => 'Tamil Nadu'],
            ['id' => 3, 'code' => 'KA', 'name' => 'Karnataka', 'description' => 'Karnataka'],
            ['id' => 4, 'code' => 'Central', 'name' => 'MP East, Maharashtra & Odisha', 'description' => 'Maharashtra, Madhya Pradesh-East, Odisha'],
            ['id' => 5, 'code' => 'North', 'name' => 'Punjab, MP West & UP', 'description' => 'Madhya Pradesh-West, Punjab, Uttar Pradesh'],
            ['id' => 6, 'code' => 'HO', 'name' => 'Head Office', 'description' => 'Head Office'],
        ]);

        // States data
        DB::table('mst_states')->insert([
            ['id' => 1, 'name' => 'Andhra Pradesh', 'lang_code' => 'tel', 'status' => 1],
            ['id' => 2, 'name' => 'Telangana', 'lang_code' => 'tel', 'status' => 1],
            ['id' => 3, 'name' => 'Tamil Nadu', 'lang_code' => 'tam', 'status' => 1],
            ['id' => 4, 'name' => 'Karnataka', 'lang_code' => 'kan', 'status' => 1],
            ['id' => 5, 'name' => 'Odisha', 'lang_code' => 'ori', 'status' => 1],
            ['id' => 6, 'name' => 'Maharashtra', 'lang_code' => 'mar', 'status' => 1],
            ['id' => 7, 'name' => 'Madhya Pradesh', 'lang_code' => 'hin', 'status' => 1],
            ['id' => 8, 'name' => 'Panjab', 'lang_code' => 'pan', 'status' => 1],
            ['id' => 9, 'name' => 'Uttar Pradesh', 'lang_code' => 'hin', 'status' => 1],
            ['id' => 10, 'name' => 'Rajasthan', 'lang_code' => 'raj', 'status' => 1],
        ]);
        
        // GA
        DB::table('mst_gas')->insert([
            ['id' => 1, 'code' => '5.06', 'name' => 'Krishna GA', 'state_id' => 1, 'cluster_id' => 1, 'status' => 1, 'position' => 1],
            ['id' => 2, 'code' => '9.72', 'name' => 'Rangareddy GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 3],
            ['id' => 3, 'code' => '9.73', 'name' => 'Nalgonda GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 2],
            ['id' => 4, 'code' => '9.07', 'name' => 'Warangal GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 4],
            ['id' => 5, 'code' => '9.68', 'name' => 'Khammam GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 5],
            ['id' => 6, 'code' => '11.55A', 'name' => 'Mahabubnagar GA', 'state_id' => 2, 'cluster_id' => 1, 'status' => 1, 'position' => 6],
            ['id' => 7, 'code' => '11.46', 'name' => 'Thiruvanamalai GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 7],
            ['id' => 8, 'code' => '11.49', 'name' => 'Tanjavur GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 8],
            ['id' => 9, 'code' => '11.52', 'name' => 'Dindigal GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 9],
            ['id' => 10, 'code' => '11.47', 'name' => 'Perambalur GA', 'state_id' => 3, 'cluster_id' => 2, 'status' => 1, 'position' => 10],
            ['id' => 11, 'code' => '5.08', 'name' => 'Belgaum GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 12],
            ['id' => 12, 'code' => '5.05', 'name' => 'Tumkur GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 13],
            ['id' => 13, 'code' => '11.02', 'name' => 'Chikkballapur GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 14],
            ['id' => 14, 'code' => '11.55B', 'name' => 'Yadgir GA', 'state_id' => 4, 'cluster_id' => 3, 'status' => 1, 'position' => 11],
            ['id' => 15, 'code' => '11.38', 'name' => 'Rayagada GA', 'state_id' => 5, 'cluster_id' => 4, 'status' => 1, 'position' => 15],
            ['id' => 16, 'code' => '11.32', 'name' => 'Chandrapur GA', 'state_id' => 6, 'cluster_id' => 4, 'status' => 1, 'position' => 16],
            ['id' => 17, 'code' => '11.27', 'name' => 'Sagar GA', 'state_id' => 7, 'cluster_id' => 4, 'status' => 1, 'position' => 17],
            ['id' => 18, 'code' => '11.25', 'name' => 'Chhindwara GA', 'state_id' => 7, 'cluster_id' => 4, 'status' => 1, 'position' => 18],
            ['id' => 19, 'code' => '11.26', 'name' => 'Jabalpur GA', 'state_id' => 7, 'cluster_id' => 4, 'status' => 1, 'position' => 19],
            ['id' => 20, 'code' => '11.22A', 'name' => 'Agar Malwa GA', 'state_id' => 7, 'cluster_id' => 5, 'status' => 1, 'position' => 20],
            ['id' => 21, 'code' => '11.04', 'name' => 'Tarn Taran GA', 'state_id' => 8, 'cluster_id' => 5, 'status' => 1, 'position' => 21],
            ['id' => 22, 'code' => '11.58', 'name' => 'Amroha GA', 'state_id' => 9, 'cluster_id' => 5, 'status' => 1, 'position' => 22],
            ['id' => 23, 'code' => '11.59', 'name' => 'Kasganj GA', 'state_id' => 9, 'cluster_id' => 5, 'status' => 1, 'position' => 23],
            ['id' => 24, 'code' => '11.22B', 'name' => 'Jhalawar GA', 'state_id' => 10, 'cluster_id' => 5, 'status' => 1, 'position' => 24],
            ['id' => 25, 'code' => '0000', 'name' => 'Head Office', 'state_id' => null, 'cluster_id' => 6, 'status' => 1, 'position' => 25],
        ]);

        // Districts
        DB::table('mst_districts')->insert([
            ['code'=> '1', 'name' => 'Krishna',  'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '54', 'name' => 'NTR', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],
            ['code'=> '55', 'name' => 'Eluru', 'state_id' => 1, 'cluster_id' => 1, 'ga_id' => 1, 'status' => 1],

            ['code'=> '7', 'name' => 'Medchal Malkajgiri', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 2, 'status' => 1],
            ['code'=> '11', 'name' => 'Rangareddy', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 2, 'status' => 1],
            ['code'=> '10', 'name' => 'Vikarabad', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 2, 'status' => 1],
            
            ['code'=> '9', 'name' => 'Nalgonda', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 3, 'status' => 1],
            ['code'=> '8', 'name' => 'Suryapet', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 3, 'status' => 1],
            ['code'=> '4', 'name' => 'Yadadri Bhuvanagir', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 3, 'status' => 1],
            
            ['code'=> '14', 'name' => 'J Boopalpally', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 4, 'status' => 1],
            ['code'=> '13', 'name' => 'Jangaon', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 4, 'status' => 1],
            ['code'=> '16', 'name' => 'Hanamkonda', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 4, 'status' => 1],
            ['code'=> '15', 'name' => 'Mahbubabad', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 4, 'status' => 1],
            ['code'=> '17', 'name' => 'Mulug', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 4, 'status' => 1],
            ['code'=> '5', 'name' => 'Warangal', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 4, 'status' => 1],
            
            ['code'=> '12', 'name' => 'Bhadradri Kothagudem', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 5, 'status' => 1],
            ['code'=> '6', 'name' => 'Khammam', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 5, 'status' => 1],
            
            ['code'=> '61', 'name' => 'Jogulamma Gadwal', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 6, 'status' => 1],
            ['code'=> '19', 'name' => 'Mahabubnagar', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 6, 'status' => 1],
            ['code'=> '42', 'name' => 'Nagarkurnool', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 6, 'status' => 1],
            ['code'=> '64', 'name' => 'Narayanpet', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 6, 'status' => 1],
            ['code'=> '63', 'name' => 'Wanaparthy', 'state_id' => 2, 'cluster_id' => 1, 'ga_id' => 6, 'status' => 1],
            
            ['code'=> '49', 'name' => 'Kallakurichi', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 7, 'status' => 1],
            ['code'=> '47', 'name' => 'Tiruvannamalai', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 7, 'status' => 1],
            ['code'=> '48', 'name' => 'Villupuram', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 7, 'status' => 1],
            
            ['code'=> '46', 'name' => 'Padukkottai', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 8, 'status' => 1],
            ['code'=> '50', 'name' => 'Sivaganga', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 8, 'status' => 1],
            ['code'=> '51', 'name' => 'Thanjavur', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 8, 'status' => 1],
            
            ['code'=> '20', 'name' => 'Ariyalur', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 10, 'status' => 1],
            ['code'=> '21', 'name' => 'Perambalur', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 10, 'status' => 1],
            
            ['code'=> '45', 'name' => 'Dindigul', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 9, 'status' => 1],
            ['code'=> '52', 'name' => 'Karur', 'state_id' => 3, 'cluster_id' => 2, 'ga_id' => 9, 'status' => 1],
            
            ['code'=> '2', 'name' => 'Belgaum', 'state_id' => 4, 'cluster_id' => 3, 'ga_id' => 11, 'status' => 1],
            
            ['code'=> '3', 'name' => 'Tumkur', 'state_id' => 4, 'cluster_id' => 3, 'ga_id' => 12, 'status' => 1],
            
            ['code'=> '53', 'name' => 'Chikkballapur', 'state_id' => 4, 'cluster_id' => 3, 'ga_id' => 13, 'status' => 1],
            
            ['code'=> '62', 'name' => 'Yadgiri', 'state_id' => 4, 'cluster_id' => 3, 'ga_id' => 14, 'status' => 1],
            
            ['code'=> '59', 'name' => 'Bolangir', 'state_id' => 5, 'cluster_id' => 4, 'ga_id' => 15, 'status' => 1],
            ['code'=> '58', 'name' => 'Kalahandi', 'state_id' => 5, 'cluster_id' => 4, 'ga_id' => 15, 'status' => 1],
            ['code'=> '60', 'name' => 'Nuapada', 'state_id' => 5, 'cluster_id' => 4, 'ga_id' => 15, 'status' => 1],
            ['code'=> '57', 'name' => 'Rayagada', 'state_id' => 5, 'cluster_id' => 4, 'ga_id' => 15, 'status' => 1],

            ['code'=> '56', 'name' => 'Chandrapur', 'state_id' => 6, 'cluster_id' => 4, 'ga_id' => 16, 'status' => 1],
            ['code'=> '18', 'name' => 'Wardha', 'state_id' => 6, 'cluster_id' => 4, 'ga_id' => 16, 'status' => 1],
            
            ['code'=> '39', 'name' => 'Hoshangabad', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 17, 'status' => 1],
            ['code'=> '40', 'name' => 'Narsinghpur', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 17, 'status' => 1],
            ['code'=> '28', 'name' => 'Sagar', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 17, 'status' => 1],
            ['code'=> '41', 'name' => 'Vidisha', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 17, 'status' => 1],
            
            ['code'=> '33', 'name' => 'Balaghat', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 18, 'status' => 1],
            ['code'=> '31', 'name' => 'Betul', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 18, 'status' => 1],
            ['code'=> '26', 'name' => 'Chhindwara', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 18, 'status' => 1],
            ['code'=> '32', 'name' => 'Seoni', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 18, 'status' => 1],

            ['code'=> '34', 'name' => 'Damoh', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 19, 'status' => 1],
            ['code'=> '38', 'name' => 'Dindori', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 19, 'status' => 1],
            ['code'=> '27', 'name' => 'Jabalpur', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 19, 'status' => 1],
            ['code'=> '35', 'name' => 'Katni', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 19, 'status' => 1],
            ['code'=> '36', 'name' => 'Mandla', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 19, 'status' => 1],
            ['code'=> '37', 'name' => 'Umaria', 'state_id' => 7, 'cluster_id' => 4, 'ga_id' => 19, 'status' => 1],

            ['code'=> '25', 'name' => 'Agar Malwa', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 20, 'status' => 1],
            ['code'=> '30', 'name' => 'Mandasaur', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 20, 'status' => 1],
            ['code'=> '29', 'name' => 'Neemuch', 'state_id' => 7, 'cluster_id' => 5, 'ga_id' => 20, 'status' => 1],

            ['code'=> '24', 'name' => 'Tarn Taran', 'state_id' => 8, 'cluster_id' => 5, 'ga_id' => 21, 'status' => 1],
            
            ['code'=> '23', 'name' => 'Amroha', 'state_id' => 9, 'cluster_id' => 5, 'ga_id' => 22, 'status' => 1],
            ['code'=> '22', 'name' => 'Sambhal', 'state_id' => 9, 'cluster_id' => 5, 'ga_id' => 22, 'status' => 1],

            ['code'=> '44', 'name' => 'Kasganj', 'state_id' => 9, 'cluster_id' => 5, 'ga_id' => 23, 'status' => 1],

            ['code'=> '43', 'name' => 'Jhalawar', 'state_id' => 10, 'cluster_id' => 5, 'ga_id' => 24, 'status' => 1],
        ]);

        // Industrial Areas
        DB::table('mst_industrial_areas')->insert([
            ['id' => '1','ga_id' => '1','name' => 'Mallavalli','created_at' => NULL,'updated_at' => '2025-08-01 10:41:43','created_by' => NULL],
            ['id' => '2','ga_id' => '1','name' => 'Surampalli','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '3','ga_id' => '1','name' => 'Gannavaram','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '4','ga_id' => '1','name' => 'Veerapanenigudem','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '5','ga_id' => '1','name' => 'Pedaavutapalli','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '6','ga_id' => '1','name' => 'Ravicherla','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '7','ga_id' => '1','name' => 'Ibrahimpatnam','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '8','ga_id' => '1','name' => 'Vedadri','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '9','ga_id' => '1','name' => 'Jaggaiahpeta','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '10','ga_id' => '1','name' => 'Jayanthipuram ','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '11','ga_id' => '1','name' => 'Akkireddy Gudem Musunuru','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '12','ga_id' => '1','name' => 'Katrenipalle Near Chillakallu','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '13','ga_id' => '1','name' => 'Balpulapadu','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '14','ga_id' => '1','name' => 'Mallavali','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '15','ga_id' => '1','name' => 'Mudinepalle','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '16','ga_id' => '1','name' => 'Nunna','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '17','ga_id' => '1','name' => 'Nuzivid','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '18','ga_id' => '1','name' => 'Ramavarapadu','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '19','ga_id' => '3','name' => 'Kondurg','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '20','ga_id' => '3','name' => 'Kothur IDA','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '21','ga_id' => '3','name' => 'Medchal Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '22','ga_id' => '3','name' => 'Genome Valley','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '23','ga_id' => '3','name' => 'Thimapur','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '24','ga_id' => '3','name' => 'Mahankal IDA','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '25','ga_id' => '3','name' => 'Kesaram','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '26','ga_id' => '3','name' => 'Rangapur','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '27','ga_id' => '3','name' => 'ATHWELLY','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '28','ga_id' => '3','name' => 'YELLAMPET IDA','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '29','ga_id' => '3','name' => 'Maheshwaram','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '30','ga_id' => '3','name' => 'Raviryala','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '31','ga_id' => '3','name' => 'Shadnagar','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '32','ga_id' => '3','name' => 'Chandanavalli','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '33','ga_id' => '6','name' => 'Polepally','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '34','ga_id' => '6','name' => 'Bodajanampet Village','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '35','ga_id' => '6','name' => 'Sankalmaddi','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '36','ga_id' => '6','name' => 'Raipalle','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '37','ga_id' => '6','name' => 'Balanagar','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '38','ga_id' => '2','name' => 'choutuppal','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '39','ga_id' => '2','name' => 'Kondamadugu','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '40','ga_id' => '2','name' => 'Bibinagar','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '41','ga_id' => '2','name' => 'Narketpally','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '42','ga_id' => '5','name' => 'Khanapuram','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '43','ga_id' => '4','name' => 'Gorrekunta','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '44','ga_id' => '4','name' => 'Madikonda','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '45','ga_id' => '12','name' => 'Honga Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '46','ga_id' => '12','name' => 'Auto Nagar Indl Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '47','ga_id' => '12','name' => 'Waghavade Indl Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '48','ga_id' => '12','name' => 'Navage Indl Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '49','ga_id' => '12','name' => 'Kanagla Indl Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '50','ga_id' => '12','name' => 'Udyambag Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '51','ga_id' => '12','name' => 'Machhe Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '52','ga_id' => '12','name' => 'In and around city','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '53','ga_id' => '13','name' => 'Vasanthanarsapura','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '54','ga_id' => '13','name' => 'Sira','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '55','ga_id' => '13','name' => 'Anthrasanahalli Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '56','ga_id' => '13','name' => 'Herihalli Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '57','ga_id' => '13','name' => 'Kunigal Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '58','ga_id' => '14','name' => 'Kudamalakunte Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '59','ga_id' => '22','name' => 'UPSIDC GAJRAULA','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '60','ga_id' => '23','name' => 'Industrial Estate Kasganj','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '61','ga_id' => '23','name' => 'In and around city','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '62','ga_id' => '21','name' => 'Indsutrial Area, Goindwal Sahib','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '63','ga_id' => '21','name' => 'Focal Point- Tarn Taran','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '64','ga_id' => '20','name' => 'In and around city','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '65','ga_id' => '7','name' => 'Cheeyar SIPCOT','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '66','ga_id' => '7','name' => 'Tindivanam SIPCOT','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '67','ga_id' => '10','name' => 'Perambalur-Trichy NH','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '68','ga_id' => '10','name' => 'SIDCO - Elambalur','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '69','ga_id' => '9','name' => 'SIDCO Industrial Estate, Dindigul','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '70','ga_id' => '8','name' => 'Viralimalai Industrail Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '71','ga_id' => '8','name' => 'SIDCO, Palaiyapatti','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '72','ga_id' => '8','name' => 'Papanasam, Tanjore','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '73','ga_id' => '19','name' => 'Maneri Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '74','ga_id' => '19','name' => 'Khamariya, Jabalpur','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '75','ga_id' => '19','name' => 'Richhai Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '76','ga_id' => '19','name' => 'Adhartal Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '77','ga_id' => '16','name' => 'Borgoan Industrial Area, Borgaon.','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '78','ga_id' => '17','name' => 'Sidguwan Industrial Area','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '79','ga_id' => '17','name' => 'Industrial State Dabar Vidisa','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '80','ga_id' => '16','name' => 'CHINCHALA MIDC','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '81','ga_id' => '16','name' => 'TADALI MIDC','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '82','ga_id' => '16','name' => 'MIDC, DEOLI','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '83','ga_id' => '16','name' => 'GUGHUS ROAD','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '84','ga_id' => '16','name' => 'MIDC, WARDHA','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '85','ga_id' => '16','name' => 'BHADRAVATI','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '86','ga_id' => '15','name' => 'In and around city','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '87','ga_id' => '3','name' => 'Shankarpalli','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '88','ga_id' => '3','name' => 'Elikatta','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '89','ga_id' => '3','name' => 'Nandigama','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '90','ga_id' => '3','name' => 'Akkinenigudem','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '91','ga_id' => '3','name' => ' penjarla,','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '92','ga_id' => '3','name' => 'Mekaguda','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '93','ga_id' => '3','name' => 'Kodicharla','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '94','ga_id' => '3','name' => 'Chatanpally','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
            ['id' => '95','ga_id' => '3','name' => 'Mokila','created_at' => NULL,'updated_at' => NULL,'created_by' => NULL],
        ]);

        // Charge Areas
        DB::table('mst_cas')->insert([
            ['code' => '1' , 'name' => 'Agiripalli', 'ga_id' => 1, 'district_id' => 1, 'status' => 1],
            ['code' => '2' , 'name' => 'Vijayawada', 'ga_id' => 1, 'district_id' => 2, 'status' => 1],
        ]);

        // Areas
        DB::table('mst_areas')->insert([
            ['name' => 'CA-01', 'ca_id' => '1', 'status' => 1],
            ['name' => 'CA-02', 'ca_id' => '1', 'status' => 1],
            ['name' => 'CA-03', 'ca_id' => '1', 'status' => 1],
        ]);
    }
}
 