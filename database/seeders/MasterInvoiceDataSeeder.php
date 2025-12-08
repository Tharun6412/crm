<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterInvoiceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // mst Taxes
        DB::table('mst_taxes')->insert([
            ['id' => '1', 'name' => 'GST'],
            ['id' => '2', 'name' => 'VAT'],
        ]);

        // mst pay types
        DB::table('mst_pay_types')->insert([
            ['id' => '1', 'name' => 'Card Payment'],
            ['id' => '2', 'name' => 'Cash Payment'],
        ]);

        // mst Bill Invoice Type
        DB::table('mst_bil_invoice_types')->insert([
            ['id' => '1', 'name' => 'Service Invoice'],
            ['id' => '2', 'name' => 'Gas Invoice'],
        ]);

        // mst bill status
        DB::table('mst_bil_status')->insert([
            ['id' => '1', 'name' => 'Paid'],
            ['id' => '2', 'name' => 'Partially Paid'],
            ['id' => '3', 'name' => 'Not Paid'],
        ]);

        // mst bil invoice item types
        DB::table('mst_bil_invoice_item_types')->insert([
            ['name' => 'Consumer Connection'],
            ['name' => 'Consumer Services'],
            ['name' => 'Custom'],
        ]);

        // mst refund status
        DB::table('mst_ref_status')->insert([
            ['name' => 'Refund Request'],
            ['name' => 'Process'],
            ['name' => 'Approved'],
            ['name' => 'Closed'],
        ]);
    }
}
 