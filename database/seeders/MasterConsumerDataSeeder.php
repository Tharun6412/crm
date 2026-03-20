<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterConsumerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Firm Types
        DB::table('mst_firm_types')->insert([
            ['id' => '1','name' => 'Food Processing Industries','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '2','name' => 'Ceramic Industries','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '3','name' => 'Glass Industries','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '4','name' => 'Garment units and Export Houses','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '5','name' => 'Pharmaceutical Companies','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '6','name' => 'Chemical Industries','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '7','name' => 'Metal Treatment Units','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '8','name' => 'Galvanizing Industries','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '9','name' => 'Beverage Manufacturing','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '10','name' => 'Plastic Industries','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '11','name' => 'FMC Goods Manufacturers','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '12','name' => 'Oil Mills','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '13','name' => 'Printing and Dyeing units','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '14','name' => 'Others','status' => '1','type' => '1','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '15','name' => 'Hotels/Restaurants','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '16','name' => 'Hostels','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '17','name' => 'Bakeries','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '18','name' => 'Food Court','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '19','name' => 'Educational Institute','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '20','name' => 'Cold Storages','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '21','name' => 'Hospitals','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '22','name' => 'Shopping Complexes/Malls','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '23','name' => 'Industrial Pantry/Canteen','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '24','name' => 'Corporate Offices','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
            ['id' => '25','name' => 'Others','status' => '1','type' => '2','created_at' => now(),'updated_at' => NULL,'created_by' => NULL],
        ]);
        // Fuel Types
        DB::table('mst_fuel_types')->insert([
            ['id' => 1, 'name' => 'Electricity', 'position' => 12, 'spot' => 1, 'fuel_group' => 3, 'created_at' => now(), 'status' => 1],
            ['id' => 2, 'name' => 'Coal', 'position' => 7, 'spot' => 1, 'fuel_group' => 3, 'created_at' => now(), 'status' => 1],
            ['id' => 3, 'name' => 'Petrol', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 4, 'name' => 'LPG', 'position' => 1, 'spot' => 1, 'fuel_group' => 1, 'created_at' => now(), 'status' => 1],
            ['id' => 5, 'name' => 'Diesel', 'position' => 4, 'spot' => 1, 'fuel_group' => 2, 'created_at' => now(), 'status' => 1],
            ['id' => 6, 'name' => 'Kerosene', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 7, 'name' => 'Other', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 8, 'name' => 'Husk', 'position' => 11, 'spot' => 1, 'fuel_group' => 3, 'created_at' => now(), 'status' => 1],
            ['id' => 9, 'name' => 'Bio Diesel', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 10, 'name' => 'HSD', 'position' => 5, 'spot' => 1, 'fuel_group' => 2, 'created_at' => now(), 'status' => 1],
            ['id' => 11, 'name' => 'Wood', 'position' => 10, 'spot' => 1, 'fuel_group' => 3, 'created_at' => now(), 'status' => 1],
            ['id' => 12, 'name' => 'FO', 'position' => 3, 'spot' => 1, 'fuel_group' => 2, 'created_at' => now(), 'status' => 1],
            ['id' => 13, 'name' => 'General Coal', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 14, 'name' => 'Pet Coke', 'position' => 8, 'spot' => 1, 'fuel_group' => 3, 'created_at' => now(), 'status' => 1],
            ['id' => 15, 'name' => 'Bitumious Coal', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 16, 'name' => 'Briquette', 'position' => 9, 'spot' => 1, 'fuel_group' => 3, 'created_at' => now(), 'status' => 1],
            ['id' => 17, 'name' => 'LDO', 'position' => 6, 'spot' => 1, 'fuel_group' => 2, 'created_at' => now(), 'status' => 1],
            ['id' => 18, 'name' => 'Natural Gas', 'position' => NULL, 'spot' => 0, 'fuel_group' => NULL, 'created_at' => now(), 'status' => 1],
            ['id' => 19, 'name' => 'Propane', 'position' => 2, 'spot' => 1, 'fuel_group' => 1, 'created_at' => now(), 'status' => 1],
        ]);

        // mst cns status
        DB::table('mst_cns_status')->insert([
            ['id' => '1','name' => 'TR','slug' => 'tr','created_at' => NULL,'updated_at' => NULL],
            ['id' => '2','name' => 'Register','slug' => 'registered','created_at' => NULL,'updated_at' => NULL],
            ['id' => '3','name' => 'Accept','slug' => 'accepted','created_at' => NULL,'updated_at' => NULL],
            ['id' => '4','name' => 'Execute','slug' => 'executed','created_at' => NULL,'updated_at' => NULL],
            ['id' => '5','name' => 'HSC','slug' => 'hsc','created_at' => NULL,'updated_at' => NULL],
            ['id' => '6','name' => 'Activate','slug' => 'activated','created_at' => NULL,'updated_at' => NULL],
            ['id' => '7','name' => 'TD','slug' => 'td','created_at' => NULL,'updated_at' => NULL],
            ['id' => '8','name' => 'PD','slug' => 'pd','created_at' => NULL,'updated_at' => NULL],
            ['id' => '9','name' => 'Reject','slug' => 'rejected','created_at' => NULL,'updated_at' => NULL],
            ['id' => '10','name' => 'Reconnect','slug' => 'reconnect','created_at' => NULL,'updated_at' => NULL]
        ]);

        // mst titles
        DB::table('mst_titles')->insert([
            ['id' => '1', 'name' => 'Mr.', 'type' => '1'],
            ['id' => '2', 'name' => 'Mrs.', 'type' => '1'],
            ['id' => '3', 'name' => 'S/O', 'type' => '2'],
            ['id' => '4', 'name' => 'D/O', 'type' => '2'],
            ['id' => '5', 'name' => 'W/O', 'type' => '2'],
        ]);

        // mst cns nominee relations
        DB::table('mst_cns_nominee_relations')->insert([
            ['id' => '1', 'name' => 'Mother'],
            ['id' => '2', 'name' => 'Father'],
            ['id' => '3', 'name' => 'Husband'],
            ['id' => '4', 'name' => 'Wife'],
            ['id' => '5', 'name' => 'Son'],
            ['id' => '6', 'name' => 'Daughter'],
        ]);

        // mst cns Gas Required
        DB::table('mst_cns_gas_required')->insert([
            ['id' => '1', 'name' => 'Heating'],
            ['id' => '2', 'name' => 'Cooking'],
        ]);

        // dc file Types
        DB::table('dc_file_types')->insert([
           ['id' => '1', 'name' => 'Aadhar(UID)', 'type' => '1'], 
           ['id' => '2', 'name' => 'Passport', 'type' => '1'], 
           ['id' => '3', 'name' => 'Arms License', 'type' => '1'], 
           ['id' => '4', 'name' => 'Driving License', 'type' => '1'], 
           ['id' => '5', 'name' => 'Election Commission ID Card', 'type' => '1'], 
           ['id' => '6', 'name' => 'Relation card with photo, for the person whose photo is affixed', 'type' => '1'], 
           ['id' => '7', 'name' => 'CGHS/ECHS Card', 'type' => '1'], 
           ['id' => '8', 'name' => 'Pentioner Card having photo', 'type' => '1'], 
           ['id' => '9', 'name' => 'PAN Card', 'type' => '1'], 
           ['id' => '10', 'name' => 'Consent form', 'type' => '1'], 
           ['id' => '11', 'name' => 'Incorporation Certificate', 'type' => '1'], 
           ['id' => '12', 'name' => 'Rent Agreement', 'type' => '1'], 
           ['id' => '13', 'name' => 'Registration Form', 'type' => '1'], 
           ['id' => '14', 'name' => 'Others', 'type' => '1'], 
           ['id' => '15', 'name' => 'GI Image', 'type' => '1'], 
           ['id' => '16', 'name' => 'MDPE Image', 'type' => '1'], 
           ['id' => '17', 'name' => 'NOC', 'type' => '1'], 
           ['id' => '18', 'name' => 'Electricity bill(Latest)', 'type' => '1'], 
           ['id' => '19', 'name' => 'Telephone Bill(Latest)', 'type' => '1'], 
           ['id' => '20', 'name' => 'Water Bill(Latest)', 'type' => '1'], 
           ['id' => '21', 'name' => 'Reg. Sale deed', 'type' => '1'], 
           ['id' => '22', 'name' => 'House Tax Receipts', 'type' => '1'], 
           ['id' => '23', 'name' => 'Allotment Letter', 'type' => '1'], 
           ['id' => '24', 'name' => 'Meter Image', 'type' => '2'], 
           ['id' => '25', 'name' => 'Isometric Image', 'type' => '2'], 
           ['id' => '26', 'name' => 'HSC', 'type' => '2'], 
           ['id' => '27', 'name' => 'Activate', 'type' => '2'], 
           ['id' => '28', 'name' => 'Bill Image', 'type' => '3'],
           ['id' => '29', 'name' => 'Load Assessment Sheet', 'type' => '4'],
           ['id' => '30', 'name' => 'Offer', 'type' => '4'],
           ['id' => '31', 'name' => 'GSA', 'type' => '4'],
           ['id' => '32', 'name' => 'Application Form', 'type' => '1'],
        ]);

        // mst cns meter status
        DB::table('mst_cns_meter_status')->insert([
            ['id' => 1, 'name' => 'Active'],
            ['id' => 2, 'name' => 'In-Active'],
            ['id' => 3, 'name' => 'Replaced'],
        ]);

        // mst cns scheme payments
        DB::table('mst_cns_scheme_payments')->insert([
            ['id' => 1, 'name' => 'Full Payment'],
            ['id' => 2, 'name' => 'EMI Payment'],
            ['id' => 3, 'name' => 'Rental Payment'],
        ]);

        // mst sd payment status
        DB::table('mst_sd_payment_status')->insert([
            ['id' => 1, 'name' => 'Paid'],
            ['id' => 2, 'name' => 'In Progress'],
            ['id' => 3, 'name' => 'Reversed'],
        ]);

        // mst_cns_geyser_status
        DB::table('mst_cns_geyser_status')->insert([
            ['id' => 1, 'name' => 'Register'],
            ['id' => 2, 'name' => 'Execute'],
            ['id' => 3, 'name' => 'Active'],
            ['id' => 4, 'name' => 'Disconnect'],
        ]);

        // mst refund status
        DB::table('mst_ref_status')->insert([
            ['id' => 1, 'name' => 'Request'],
            ['id' => 2, 'name' => 'Process'],
            ['id' => 3, 'name' => 'Approved'],
            ['id' => 4, 'name' => 'Closed'],
        ]);
    }
}
 