<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterPaymentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Payment gateways
        DB::table('mst_payment_gateways')->insert([
            ['id' => 1, 'gateway' => 'Easebuzz'],
            ['id' => 2, 'gateway' => 'BBPS'],
        ]);

        // Pay modules
        DB::table('mst_pay_modules')->insert([
            ['id' => 1, 'name' => 'Pay Deposit'],
            ['id' => 2, 'name' => 'Security Deposit'],
            ['id' => 3, 'name' => 'Gas Invoice'],
            ['id' => 4, 'name' => 'Invoice'],
            ['id' => 5, 'name' => 'Recharge'],
        ]);

        // mst_pay_transaction_status
        DB::table('mst_pay_transaction_status')->insert([
            ['id' => 1, 'name' => 'Initiated'],
            ['id' => 2, 'name' => 'Success'],
            ['id' => 3, 'name' => 'Failed'],
            ['id' => 4, 'name' => 'Cancelled'],
        ]);

        // mst connection type Required
        DB::table('mst_connection_types')->insert([
            ['id' => '1', 'name' => 'POSTPAID'],
            ['id' => '2', 'name' => 'PREPAID'],
        ]);
    }
}
 