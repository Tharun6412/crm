<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        DB::table('adm_roles')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'position' => 1, 'status' => 1],
            ['id' => 2, 'name' => 'Admin', 'position' => 2, 'status' => 1],
        ]);

        // User Status
        DB::table('adm_user_status')->insert([
            ['id' => 1, 'name' => 'Active'],
            ['id' => 2, 'name' => 'InActive'],
            ['id' => 3, 'name' => 'Registered'],
            ['id' => 4, 'name' => 'Reset / Change Password'],
        ]);

        // Departments
        DB::table('mst_departments')->insert([
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
        
        // Users
        DB::table('users')->insert([
            ['id' => 1, 'first_name' => 'Super', 'last_name' => 'Admin', 'email' => 'superadmin@meghagas.com', 'email_verified_at' => null, 'password' => Hash::make('12345678'), 'emp_id' => 'superadmin', 'mobile' => '9999999999', 'mobile_b' => NULL, 'gender' => NULL, 'dob' => NULL, 'image' => NULL, 'type' => 1, 'status_id' => 1, 'department_id' => 18, 'remember_token' => NULL, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'first_name' => 'Admin', 'last_name' => 'Megha', 'email' => 'admin@meghagas.com.com', 'email_verified_at' => null, 'password' => Hash::make(12345678), 'emp_id' => 'admin', 'mobile' => '8888888888', 'mobile_b' => NULL, 'gender' => NULL, 'dob' => NULL, 'image' => NULL, 'type' => 1, 'status_id' => 1, 'department_id' => 18, 'remember_token' => NULL, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // User roles
        DB::table('adm_user_roles')->insert([
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
        ]);
    }
}
 