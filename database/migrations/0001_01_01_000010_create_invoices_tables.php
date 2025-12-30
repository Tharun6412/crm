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
        // bil Invoices
        Schema::create('bil_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->index()->nullable()->constrained(table:'mst_bil_invoice_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->string('invoice_number', length:32)->index()->unique()->nullable();
            $table->date('invoice_date')->nullable();
            $table->double('base_amount')->nullable();
            $table->double('taxable_amount')->nullable();
            $table->foreignId('tax_id')->index()->nullable()->constrained(table:'mst_taxes')->noActionOnDelete()->noActionOnUpdate();
            $table->double('tax_value')->nullable();
            $table->double('tax_amount')->nullable();
            $table->double('credit_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('balance_amount')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_bil_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('parent_invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // bil Invoice Items
        Schema::create('bil_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('item_id')->index()->nullable()->constrained(table:'mst_bil_invoice_items')->noActionOnDelete()->noActionOnUpdate();
            $table->string('description', length:225)->nullable();
            $table->double('quantity')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total_price')->nullable();
            $table->timestamps();
        });

        // bil Invoice Consumption
        Schema::create('bil_invoice_consumption', function(Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('meter_id')->index()->nullable()->constrained(table:'cns_consumer_meters')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('price_history_id')->index()->nullable()->constrained(table:'mst_price_history')->noActionOnDelete()->noActionOnUpdate();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->integer('days')->nullable();
            $table->double('prev_reading')->nullable();
            $table->double('curr_reading')->nullable();
            $table->double('consumption')->nullable();
            $table->double('cf')->nullable();
            $table->foreignId('meter_change_id')->index()->nullable()->constrained(table:'cns_meter_changes')->noActionOnDelete()->noActionOnUpdate();
            $table->double('old_consumption')->nullable();
            $table->double('net_consumption')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total_price')->nullable();
            $table->foreignId('file_id')->index()->nullable()->constrained(table:'dc_files')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // bil Invoice Consumption Details
        Schema::create('bil_invoice_consumption_details', function(Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_consumption_id')->index()->nullable()->constrained(table:'bil_invoice_consumption')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('price_history_id')->index()->nullable()->constrained(table:'mst_price_history')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('days')->nullable();
            $table->double('consumption')->nullable();
            $table->double('cf')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total_price')->nullable();
            $table->timestamps();
        });

        // bil ledger
        Schema::create('bil_ledger', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->bigInteger('legible_id')->nullable();
            $table->string('legible_type', length:225)->nullable();
            $table->string('description', length:225)->nullable();
            $table->double('credit')->nullable();
            $table->double('debit')->nullable();
            $table->double('balance')->nullable();
            $table->timestamps();
        });
        
        // bil credit notes
        Schema::create('bil_credit_notes', function(Blueprint $table) {
            $table->id();
            $table->string('code', length:20);
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('type')->nullable()->comment('1 = Credit, 2= Debit');
            $table->string('notes', length:225)->nullable();
            $table->double('base_amount')->nullable();
            $table->foreignId('tax_id')->index()->nullable()->constrained(table:'mst_taxes')->noActionOnDelete()->noActionOnUpdate();
            $table->decimal('tax_value', total:8, places:3);
            $table->double('tax_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->integer('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // bil credit items
        Schema::create('bil_credit_items', function(Blueprint $table) {
            $table->id();
            $table->foreignId('credit_note_id')->index()->nullable()->constrained(table:'bil_credit_notes')->noActionOnDelete()->noActionOnUpdate();
            $table->string('description', length:225)->nullable();
            $table->decimal('quantity', total:8, places:3);
            $table->double('unit_price')->nullable();
            $table->integer('total_price')->nullable();
            $table->timestamps();
        });

        // bil credit items
        Schema::create('bil_invoice_cancels', function(Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->string('reason', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // pay invoice payments
        Schema::create('pay_invoice_payments', function(Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->date('payment_date')->nullable();
            $table->foreignId('payment_type_id')->index()->nullable()->constrained(table:'mst_pay_types')->noActionOnDelete()->noActionOnUpdate();
            $table->string('transaction_id', length:225)->nullable();
            $table->double('amount')->nullable();
            $table->double('balance')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_pay_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // pay invoice payments
        Schema::create('pay_payment_cheques', function(Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->index()->nullable()->constrained(table:'pay_invoice_payments')->noActionOnDelete()->noActionOnUpdate();
            $table->string('chq_number', length:32)->nullable();
            $table->date('chq_date')->nullable();
            $table->double('amount')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_pay_cheque_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // pay invoice payments
        Schema::create('pay_payment_reversals', function(Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->index()->nullable()->constrained(table:'pay_invoice_payments')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // pay transactions
        Schema::create('pay_transactions', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnDelete()->noActionOnUpdate();
            $table->string('order_id', length:32)->nullable();
            $table->integer('through')->nullable();
            $table->double('amount')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_pay_transaction_status')->noActionOnDelete()->noActionOnUpdate();
            $table->date('transaction_date')->nullable();
            $table->string('transaction_ref', length:32)->nullable();
            $table->string('bank_ref', length:32)->nullable();
            $table->string('transaction_no', length:32)->nullable();
            $table->double('paid_amount')->nullable();
            $table->string('payment_mode', length:32)->nullable();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // bill state invoice counter
        Schema::create('bil_invoice_counter', function(Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('tax_group_id')->nullable()->index()->constrained(table:'mst_tax_groups')->noActionOnDelete()->noActionOnUpdate();
            $table->string('invoice_code')->nullable();
            $table->double('count')->nullable();
            $table->timestamps();
        });

        // ref refunds
        Schema::create('ref_refunds', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->string('request_no', length:16)->nullable();
            $table->double('sd_paid')->nullable();
            $table->double('outstanding_amount')->nullable();
            $table->double('disconnection_amount')->nullable();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnUpdate()->noActionOnDelete();
            $table->double('refund_amount')->nullable();
            $table->foreignId('payment_type_id')->index()->nullable()->constrained(table:'mst_pay_types')->noActionOnUpdate()->noActionOnDelete();
            $table->string('transaction_id', length:32)->nullable();
            $table->date('transaction_date')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_ref_status')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        // ref refund status
        Schema::create('ref_refund_status', function(Blueprint $table) {
            $table->id();
            $table->foreignId('refund_id')->index()->nullable()->constrained(table:'ref_refunds')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_ref_status')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // cns consumer sd payments
        Schema::create('cns_consumer_sd_payments', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('payment_type_id')->index()->nullable()->constrained(table:'mst_pay_types')->noActionOnUpdate()->noActionOnDelete();
            $table->string('transaction_number', length:32)->nullable();
            $table->double('amount')->nullable();
            $table->integer('emi_no')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_sd_payment_status')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnUpdate()->noActionOnDelete();
            $table->double('balance')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // cns consumer sd reveals
        Schema::create('cns_consumer_sd_reversals', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_sd_payment_id')->nullable()->index()->constrained(table:'cns_consumer_sd_payments')->noActionOnDelete()->noActionOnUpdate();
            $table->string('reason', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        // cns geysers
        Schema::create('cns_geysers', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->string('code', length:16)->nullable();
            $table->double('amount')->nullable();
            $table->foreignId('invoice_id')->index()->nullable()->constrained(table:'bil_invoices')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_cns_geyser_status')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // cns geyser status
        Schema::create('cns_geyser_status', function(Blueprint $table) {
            $table->id();
            $table->foreignId('geyser_id')->index()->nullable()->constrained(table:'cns_geysers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_cns_geyser_status')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('ref_refund_status');
        Schema::dropIfExists('ref_refunds');
        Schema::dropIfExists('bil_invoice_counter');
        Schema::dropIfExists('pay_transactions');
        Schema::dropIfExists('pay_payment_reversals');
        Schema::dropIfExists('pay_payment_cheques');
        Schema::dropIfExists('pay_invoice_payments');
        Schema::dropIfExists('bil_invoice_cancels');
        Schema::dropIfExists('bil_credit_items');
        Schema::dropIfExists('bil_credit_notes');
        Schema::dropIfExists('bil_ledger');
        Schema::dropIfExists('bil_invoice_consumption_details');
        Schema::dropIfExists('bil_invoice_consumption');
        Schema::dropIfExists('bil_invoice_items');
        Schema::dropIfExists('cns_consumer_sd_reversals');
        Schema::dropIfExists('cns_consumer_sd_payments');
        Schema::dropIfExists('cns_geyser_status');
        Schema::dropIfExists('cns_geysers');
        Schema::dropIfExists('bil_invoices');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
