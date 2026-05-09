<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Advance payments
         */
        Schema::create('pay_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table: 'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->double('advance_amount')->nullable();
            $table->timestamp('updated_at');
        });

        /**
         * Advance transactions
         */
        Schema::create('pay_advance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->index()->nullable()->constrained(table:'pay_invoice_payments')->noActionOnDelete()->noActionOnUpdate();
            $table->double('amount')->nullable();
            $table->double('balance')->nullable();
            $table->timestamps();
        });

        /**
         * Alter bil_invoices table
         */
        Schema::table('bil_invoices', function (Blueprint $table) {
            $table->double('advance_amount')->after('total_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_advances');
    }
};
