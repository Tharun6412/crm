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
            ['id' => 1, 'name' => 'VAT'],
            ['id' => 2, 'name' => 'GST'],
            ['id' => 3, 'name' => 'IGST'],
            ['id' => 4, 'name' => 'CST'],
            ['id' => 5, 'name' => 'Excise Duty'],
        ]);

        // mst Bill Invoice Type
        DB::table('mst_bil_invoice_types')->insert([
            ['id' => 1, 'name' => 'Gas Bills'],
            ['id' => 2, 'name' => 'Service Invoice'],
            ['id' => 3, 'name' => 'Late Payment Charges'],
            ['id' => 4, 'name' => 'Rental Charges'],
            ['id' => 5, 'name' => 'SD EMI'],
            ['id' => 6, 'name' => 'Custom Invoice'],
        ]);

        // mst pay types
        DB::table('mst_pay_types')->insert([
            ['id' => 1, 'name' => 'Cash Payment'],
            ['id' => 2, 'name' => 'Online'],
            ['id' => 3, 'name' => 'Cheque'],
            ['id' => 4, 'name' => 'Bank Transfer (IMPS/NEFT/RTGS)'],
            ['id' => 5, 'name' => 'Card Payment'],
            ['id' => 6, 'name' => 'UPI'],
            ['id' => 7, 'name' => 'DD / PO'],
            ['id' => 8, 'name' => 'BG / LC'],
            ['id' => 9, 'name' => 'TDS'],
            ['id' => 10, 'name' => 'Wallet'],
            ['id' => 11, 'name' => 'Bad Debts'],
            ['id' => 12, 'name' => 'SD EMI'],
            ['id' => 13, 'name' => 'From SD'],
            ['id' => 14, 'name' => 'To Invoice'],
        ]);

        // mst bill status
        DB::table('mst_bil_status')->insert([
            ['id' => 1, 'name' => 'Paid'],
            ['id' => 2, 'name' => 'Not Paid'],
            ['id' => 3, 'name' => 'Partially Paid'],
            ['id' => 4, 'name' => 'Cancelled'],
        ]);

        // mst Payment status
        DB::table('mst_pay_status')->insert([
            ['id' => 1, 'name' => 'Completed'],
            ['id' => 2, 'name' => 'Progress'],
            ['id' => 3, 'name' => 'Reversed'],
        ]);

        // mst bil invoice item types
        DB::table('mst_bil_invoice_item_types')->insert([
            ['id' => 1, 'name' => 'Consumer Connection'],
            ['id' => 2, 'name' => 'Consumer Services'],
            ['id' => 3, 'name' => 'Custom items'],
        ]);

        // mst refund status
        DB::table('mst_pay_cheque_status')->insert([
            ['id' => 1, 'name' => 'Open'],
            ['id' => 2, 'name' => 'Clear'],
            ['id' => 3, 'name' => 'Bounce'],
            ['id' => 4, 'name' => 'Cancel'],
        ]);
        
        // mst_pay_transaction_status
        DB::table('mst_pay_transaction_status')->insert([
            ['id' => 1, 'name' => 'No response'],
            ['id' => 2, 'name' => 'Success'],
            ['id' => 3, 'name' => 'Fail'],
        ]);
    }
}
 