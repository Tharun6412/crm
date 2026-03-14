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

        // mst invoice items
        DB::table('mst_bil_invoice_items')->insert([
            ['id' => 1,'type_id' => 2,'name' => 'Internal GI pipe and installation Removal for Renovation','hsn' => 996913,'basic' => 350,'tax_value' => 18,'price' => 413,'status' => 1,'created_by' => 1],
            ['id' => 2,'type_id' => 2,'name' => 'External OR internal /Riser GI pipe Removal for Renovation /Building Extension OR Modification','hsn' => 996913,'basic' => 350,'tax_value' => 18,'price' => 413,'status' => 1,'created_by' => 1],
            ['id' => 3,'type_id' => 2,'name' => 'PE pipeline laying through Boring','hsn' => 996913,'basic' => 350,'tax_value' => 18,'price' => 413,'status' => 1,'created_by' => 1],
            ['id' => 4,'type_id' => 2,'name' => 'Wire braded flexible Hose Replacement','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 5,'type_id' => 2,'name' => 'Gas Tap replacement/appliance value/Isolation Value','hsn' => 996913,'basic' => 200,'tax_value' => 18,'price' => 236,'status' => 1,'created_by' => 1],
            ['id' => 6,'type_id' => 2,'name' => 'Disconnection/Deactivation for Supply of Gas','hsn' => 996913,'basic' => 100,'tax_value' => 18,'price' => 118,'status' => 1,'created_by' => 1],
            ['id' => 7,'type_id' => 2,'name' => 'Reconnection/Restoration for Supply of Gas','hsn' => 996913,'basic' => 100,'tax_value' => 18,'price' => 118,'status' => 1,'created_by' => 1],
            ['id' => 8,'type_id' => 1,'name' => 'Name Transfer Charges(Except Death)','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 9,'type_id' => 1,'name' => 'New tenant connection (Re- conversion)','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 10,'type_id' => 2,'name' => 'Old Stove Exchange','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 11,'type_id' => 2,'name' => 'Stove Service','hsn' => 996913,'basic' => 150,'tax_value' => 18,'price' => 177,'status' => 1,'created_by' => 1],
            ['id' => 12,'type_id' => 2,'name' => 'Stove Service Commercial','hsn' => 996913,'basic' => 500,'tax_value' => 18,'price' => 590,'status' => 1,'created_by' => 1],
            ['id' => 13,'type_id' => 2,'name' => 'Piping Service Commercial','hsn' => 996913,'basic' => 1000,'tax_value' => 18,'price' => 1180,'status' => 1,'created_by' => 1],
            ['id' => 14,'type_id' => 2,'name' => 'Renovation charges','hsn' => 996913,'basic' => 1500,'tax_value' => 18,'price' => 1770,'status' => 1,'created_by' => 1],
            ['id' => 15,'type_id' => 2,'name' => 'Demolition charges','hsn' => 996913,'basic' => 1500,'tax_value' => 18,'price' => 1770,'status' => 1,'created_by' => 1],
            ['id' => 16,'type_id' => 1,'name' => 'Domestic Registration Charges','hsn' => 999511,'basic' => 254.24,'tax_value' => 18,'price' => 300,'status' => 1,'created_by' => 1],
            ['id' => 17,'type_id' => 3,'name' => 'Cheque Dishonour/Bounce Charges','hsn' => 996913,'basic' => 423.73,'tax_value' => 18,'price' => 500,'status' => 1,'created_by' => 1],
            ['id' => 18,'type_id' => 2,'name' => 'Permanent disconnection Voluntary or any Personal reason','hsn' => 996913,'basic' => 500,'tax_value' => 18,'price' => 590,'status' => 1,'created_by' => 1],
            ['id' => 19,'type_id' => 3,'name' => 'Permanent disconnection Due to non-payment of invoices','hsn' => 996913,'basic' => 1000,'tax_value' => 18,'price' => 1180,'status' => 1,'created_by' => 1],
            ['id' => 20,'type_id' => 2,'name' => 'Gl / Copper Pipeline Support Removal / Clamp Removal','hsn' => 996913,'basic' => 1000,'tax_value' => 18,'price' => 1180,'status' => 1,'created_by' => 1],
            ['id' => 21,'type_id' => 3,'name' => 'Meter Damage / Bypass / Tempering / Loss','hsn' => 996913,'basic' => 2000,'tax_value' => 18,'price' => 2360,'status' => 1,'created_by' => 1],
            ['id' => 22,'type_id' => 2,'name' => 'Gl Pipeline /Copper Pipeline Extension/Removal','hsn' => 996913,'basic' => 2000,'tax_value' => 18,'price' => 2360,'status' => 1,'created_by' => 1],
            ['id' => 23,'type_id' => 3,'name' => 'Unauthorized Tap Off / Tee Connected Either Using Rubber Tube or Any Other Mode','hsn' => 996913,'basic' => 1500,'tax_value' => 18,'price' => 1770,'status' => 1,'created_by' => 1],
            ['id' => 24,'type_id' => 3,'name' => 'Extension of Flexible Rubber Hose beyond 1.5 Meters or Concealing of Rubber Hose / GI Pipeline','hsn' => 996913,'basic' => 1000,'tax_value' => 18,'price' => 1180,'status' => 1,'created_by' => 1],
            ['id' => 25,'type_id' => 1,'name' => 'Change of scheme - After registration Incl. of GST','hsn' => 996913,'basic' => 254.24,'tax_value' => 18,'price' => 300,'status' => 1,'created_by' => 1],
            ['id' => 26,'type_id' => 2,'name' => 'Appliance Valve Replacement','hsn' => 996913,'basic' => 296.61,'tax_value' => 18,'price' => 350,'status' => 1,'created_by' => 1],
            ['id' => 27,'type_id' => 1,'name' => 'Name Transfer Charges (Re-Sale / Internal)','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 28,'type_id' => 2,'name' => 'HDPE Guard','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 29,'type_id' => 3,'name' => 'Disconnection / Re-connection Charges In Case of Payment Default','hsn' => 996913,'basic' => 500,'tax_value' => 18,'price' => 590,'status' => 1,'created_by' => 1],
            ['id' => 30,'type_id' => 2,'name' => 'Removal & Refitting of Gas Connection (Flat) (Additional Material on chargeable basis)','hsn' => 996913,'basic' => 2000,'tax_value' => 18,'price' => 2360.00,'status' => 1,'created_by' => 1],
            ['id' => 31,'type_id' => 2,'name' => 'Removal & Refitting of Gas Connection (Bunglow/Tenament/Row House) (Additional Material on chargeable basis)','hsn' => 996913,'basic' => 3000,'tax_value' => 18,'price' => 3540,'status' => 1,'created_by' => 1],
            ['id' => 32,'type_id' => 2,'name' => 'personal reason-without dismantling of GI installation(valid for 6 months)','hsn' => 996913,'basic' => 250,'tax_value' => 18,'price' => 295,'status' => 1,'created_by' => 1],
            ['id' => 33,'type_id' => 2,'name' => 'Disconnection Charges','hsn' => 996913,'basic' => 0,'tax_value' => 18,'price' => 0,'status' => 1,'created_by' => 1],
            ['id' => 34,'type_id' => 2,'name' => 'Geyser Connection Charges','hsn' => 996913,'basic' => 2542.37,'tax_value' => 18,'price' => 3000,'status' => 1,'created_by' => 1],
            ['id' => 35,'type_id' => 3,'name' => 'Extension of Flexible Rubber Hose beyond 1.5 Meters or Concealing of Rubber Hose / GI Pipeline','hsn' => 996913,'basic' => 1000,'tax_value' => 18,'price' => 1180,'status' => 1,'created_by' => 1],
            ['id' => 36,'type_id' => 1,'name' => 'Prepaid Conversion Charges.','hsn' => 996913,'basic' => 423.73,'tax_value' => 18,'price' => 500,'status' => 1,'created_by' => 1],
        ]);
    }
}
 