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
        /**
         * Payment gateways
         */
        Schema::create('mst_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->nullable(); // Easebuzz, BBPS
            $table->enum('mode', ['test', 'production'])->nullable();
            $table->json('credentials')->nullable(); // encrypted
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['gateway', 'mode']);
        });

        /**
         * Payment gateway details - sub merchants
         */
        Schema::create('mst_payment_gateway_details', function(Blueprint $table) {
            $table->id();
            $table->foreignId('payment_gateway_id')->nullable()->index()->constrained(table:'mst_payment_gateways')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('sub_merchant_id', length: 32)->nullable();
            $table->timestamps();
        });

        // Payment gateway payment modules - mst_pay_modules
        Schema::create('mst_pay_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 32);
        });

        /**
         * Payment gateway transactions - pay_transactions
         */
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
            $table->string('remarks', length:128)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('mst_payment_gateways');
        Schema::dropIfExists('mst_payment_gateway_details');
        Schema::dropIfExists('pay_transactions');
        Schema::dropIfExists('mst_pay_modules');
    }
};
