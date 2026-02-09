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
        // New Connection types
        Schema::create('mst_connection_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 32);
        });

        // New Prepaid data
        Schema::create('cns_prepaid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->date('conversion_date')->nullable();
            $table->decimal('bonus', 11, 2)->nullable();
            $table->boolean('bonus_status')->nullable();
            $table->boolean('bonus_date')->nullable();
            $table->double('balance')->nullable();
            $table->dateTime('balance_date')->nullable();
            $table->timestamps();
        });

        // Consumer recharge
        Schema::create('pay_recharges', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->date('recharge_date')->nullable();
            $table->double('amount')->nullable();
            $table->double('balance')->nullable();
            $table->foreignId('payment_type_id')->nullable()->index()->constrained(table:'mst_pay_types')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('transaction_id')->nullable()->index()->constrained(table: 'pay_transactions')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });



        /**
         * Price groups
         */
        // mst_price_groups
        Schema::create('mst_price_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 32)->nullable();
            $table->string('description', length: 120)->nullable();
            $table->foreignId('ga_id')->index()->nullable()->constrained(table: 'mst_gas')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('segment_id')->index()->nullable()->constrained(table: 'mst_segments')->noActionOnUpdate()->noActionOnDelete();
            $table->double('basic')->nullable();
            $table->decimal('vat', 8, 2)->nullable();
            $table->double('price')->nullable();
            $table->date('effective_from')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        // mst_price_group_history
        Schema::create('mst_price_group_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_group_id')->nullable()->index()->constrained(table:'mst_price_groups')->noActionOnDelete()->noActionOnUpdate();
            $table->string('code', length: 32)->nullable();
            $table->string('description', length: 120)->nullable();
            $table->foreignId('ga_id')->index()->nullable()->constrained(table: 'mst_gas')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('segment_id')->index()->nullable()->constrained(table: 'mst_segments')->noActionOnUpdate()->noActionOnDelete();
            $table->double('basic')->nullable();
            $table->decimal('vat', 8, 2)->nullable();
            $table->double('price')->nullable();
            $table->date('effective_from')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        /**
         * MRO tables
         */
        Schema::create('mst_mro_status', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:32);
        });

        Schema::create('bil_mro_data', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->string('mro_number', length:64)->nullable()->index();
            $table->date('schedule_date')->nullable();
            $table->string('mro_data', length:400)->nullable();
            $table->foreignId('invoice_id')->nullable()->index()->constrained(table:'bil_invoices')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_mro_status')->noActionOnUpdate()->noActionOnDelete();
            $table->timestamps();
        });

        Schema::create('bil_mro_data_history', function(Blueprint $table){
            $table->id();
            $table->foreignId('mro_data_id')->nullable()->index()->constrained(table:'bil_mro_data')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_mro_status')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:400)->nullable();
            $table->dateTime('created_at')->nullable();
        });

        /**
         * Updates
         */
        // Update Scheme
        Schema::table('mst_cns_schemes', function (Blueprint $table) {
            $table->foreignId('connection_type_id')->nullable()->index()->after('rental_amount')->constrained(table:'mst_connection_types')->noActionOnUpdate()->noActionOnDelete();
            $table->decimal('bonus', 11, 2)->nullable();
        });

        // Update consumers
        Schema::table('cns_consumers', function (Blueprint $table) {
            $table->foreignId('connection_type_id')->nullable()->index()->after('segment_id')->constrained(table:'mst_connection_types')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('price_group_id')->nullable()->index()->after('status_id')->constrained(table:'mst_price_groups')->noActionOnUpdate()->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('mst_connection_types');
        Schema::dropIfExists('pay_recharges');
        Schema::dropIfExists('cns_prepaid');
        Schema::dropIfExists('bil_mro_data_history');
        Schema::dropIfExists('bil_mro_data');
        Schema::dropIfExists('mst_mro_status');
        Schema::dropIfExists('mst_price_group_history');
        Schema::dropIfExists('mst_price_groups');
    }
};
