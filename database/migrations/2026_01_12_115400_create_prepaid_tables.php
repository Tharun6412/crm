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
            $table->decimal('bonus', 4, 2)->nullable();
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
            $table->string('transaction_id', length: 128)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Update Scheme
        Schema::table('mst_cns_schemes', function (Blueprint $table) {
            $table->foreignId('connection_type_id')->nullable()->index()->after('rental_amount')->constrained(table:'mst_connection_types')->noActionOnUpdate()->noActionOnDelete();
            $table->decimal('bonus', 4, 2)->nullable();
        });

        // Update consumers
        Schema::table('cns_consumers', function (Blueprint $table) {
            $table->foreignId('connection_type_id')->nullable()->index()->after('segment_id')->constrained(table:'mst_connection_types')->noActionOnUpdate()->noActionOnDelete();
        });

        // New Onlinem Payment modules
        Schema::create('mst_pay_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 32);
        });

        // pay transactions
        Schema::create('pay_transactions', function(Blueprint $table) {
            $table->id();
            $table->foreignId('payment_module_id')->index()->nullable()->constrained(table:'mst_pay_modules')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('gateway_id')->index()->nullable()->constrained(table:'mst_payment_gateways')->noActionOnDelete()->noActionOnUpdate();
            $table->date('transaction_date')->nullable();
            $table->string('transaction_id', length:128)->nullable();
            $table->double('amount')->nullable();
            $table->foreignId('transaction_status_id')->index()->nullable()->constrained(table:'mst_pay_transaction_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('transaction_ref', length:128)->nullable();
            $table->string('bank_ref', length:128)->nullable();
            $table->string('pg_ref_id', length:128)->nullable();
            $table->double('paid_amount')->nullable();
            $table->string('payment_mode', length:32)->nullable();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
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
        Schema::dropIfExists('pay_transactions');
    }
};
