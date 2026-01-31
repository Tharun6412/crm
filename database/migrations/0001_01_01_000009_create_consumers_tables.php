<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Consumers Table
        Schema::create('cns_consumers', function(Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->nullable()->index()->constrained(table:'mst_segments')->noActionOnDelete()->noActionOnUpdate();
            $table->string('t_crn', length:16)->index()->unique()->nullable();
            $table->string('crn', length:16)->index()->unique()->nullable();
            $table->foreignId('title')->nullable()->index()->constrained(table:'mst_titles')->noActionOnDelete()->noActionOnUpdate();
            $table->string('fname', length:60)->nullable();
            $table->string('lname', length:60)->nullable();
            $table->foreignId('cof')->nullable()->index()->constrained(table:'mst_titles')->noActionOnDelete()->noActionOnUpdate();
            $table->string('cof_name', length:90)->nullable();
            $table->string('aadhar', length:16)->nullable();
            $table->string('pan', length:16)->nullable();
            $table->string('gst', length:32)->nullable();
            $table->string('email', length:90)->nullable();
            $table->string('phone', length:16)->nullable();
            $table->string('phone_alt', length:16)->nullable();
            $table->string('nominee', length:90)->nullable();
            $table->foreignId('nominee_relation_id')->index()->nullable()->constrained(table:'mst_cns_nominee_relations')->noActionOnUpdate()->noActionOnDelete();
            $table->string('hno', length:100)->nullable();
            $table->string('street', length:100)->nullable();
            $table->string('colony', length:100)->nullable();
            $table->string('city', length:100)->nullable();
            $table->string('ward', length:100)->nullable();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'mst_cas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->string('pincode', length:8)->nullable();
            $table->integer('lpg_connections')->nullable();
            $table->decimal('dcq', 8, 3)->nullable();
            $table->date('expected_date')->nullable();
            $table->decimal('distance', 8, 3)->nullable();
            $table->integer('property_type')->nullable()->comment('1 = Own, 2 = Rent, 3 = Lease');
            $table->string('owner_name', length:90)->nullable();
            $table->string('owner_phone', length:16)->nullable();
            $table->string('tenant_name', length:60)->nullable();
            $table->string('tenant_phone', length:16)->nullable();
            $table->string('tenant_email', length:60)->nullable();
            $table->foreignId('gas_required_id')->nullable()->index()->constrained(table:'mst_cns_gas_required')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('firm_type_id')->nullable()->index()->constrained(table:'mst_firm_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('fuel_id')->nullable()->index()->constrained(table:'mst_fuel_types')->noActionOnDelete()->noActionOnUpdate();
            $table->decimal('fuel_qty', 8, 3)->nullable();
            $table->decimal('peak_qty', 8, 3)->nullable();
            $table->integer('hours')->nullable();
            $table->string('req_pressure', length:50)->nullable();
            $table->string('req_flow', length:50)->nullable();
            $table->foreignId('payment_id')->nullable()->index()->constrained(table:'mst_pay_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_cns_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // cns Consumers data
        Schema::create('cns_consumer_data', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->string('lat',length:50)->nullable();
            $table->string('lng',length:50)->nullable();
            $table->timestamps();
        });

        // cns consumer meters
        Schema::create('cns_consumer_meters', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('file_id')->index()->nullable()->constrained(table:'dc_files')->noActionOnUpdate()->noActionOnDelete();
            $table->string('meter_no', length:32)->nullable();
            $table->string('meter_serial_no', length:32)->nullable();
            $table->double('initial_reading')->nullable();
            $table->dateTime('install_date')->nullable();
            $table->foreignId('install_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status')->nullable()->index()->constrained(table:'mst_cns_meter_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        
        //cns meter changes
        Schema::create('cns_meter_changes', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('meter_id')->index()->nullable()->constrained(table:'cns_consumer_meters')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('file_id')->index()->nullable()->constrained(table:'dc_files')->noActionOnUpdate()->noActionOnDelete();
            $table->double('prev_reading')->nullable();
            $table->double('end_reading')->nullable();
            $table->decimal('consumption', 8, 3)->nullable();
            $table->foreignId('new_meter_id')->index()->nullable()->constrained(table:'cns_consumer_meters')->noActionOnUpdate()->noActionOnDelete();
            $table->date('request_date')->nullable();
            $table->date('replace_date')->nullable();
            $table->string('reason', length:225)->nullable();
            $table->integer('status_id')->nullable()->comment('1 = pending, 2= Closed');
            $table->string('remarks', length:225)->nullable();
            $table->foreignId('technician_id')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        }); 

        // cns consumer schemes
        Schema::create('cns_consumer_schemes', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('scheme_id')->index()->nullable()->constrained(table:'mst_cns_schemes')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('scheme_payment_id')->index()->nullable()->constrained(table:'mst_cns_scheme_payments')->noActionOnDelete()->noActionOnUpdate();
            $table->double('security_deposit')->nullable();
            $table->double('consumption_deposit')->nullable();
            $table->double('total_deposit')->nullable();
            $table->decimal('emi_amount', total:8, places:2);
            $table->decimal('rental_amount', total:8, places:2);
            $table->double('paid_deposit')->nullable();
            $table->double('balance')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });

        // cns consumer status
        Schema::create('cns_consumer_status', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->decimal('lat',10,9)->nullable();
            $table->decimal('lng',10,9)->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_cns_status')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // cns consumer documents
        Schema::create('cns_consumer_documents', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_cns_status')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('doc_type_id')->index()->nullable()->constrained(table:'dc_file_types')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('file_id')->index()->nullable()->constrained(table:'dc_files')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:225)->nullable();
            $table->timestamps();
        });

        // cns ca counter
        Schema::create('cns_ca_counter', function(Blueprint $table) {
            $table->id();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'mst_cas')->noActionOnDelete()->noActionOnUpdate();
            $table->double('count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('cns_consumer_documents');
        Schema::dropIfExists('cns_consumer_status');
        Schema::dropIfExists('cns_consumer_schemes');
        Schema::dropIfExists('cns_meter_changes');
        Schema::dropIfExists('cns_consumer_meters');
        Schema::dropIfExists('cns_consumer_data');
        Schema::dropIfExists('cns_ca_counter');
        Schema::dropIfExists('cns_consumers');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
