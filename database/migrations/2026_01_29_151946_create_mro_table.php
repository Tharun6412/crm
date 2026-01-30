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
        Schema::create('mst_mro_status', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:32);
        });

        Schema::create('bill_mro_data', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->string('mro_number', length:64)->nullable()->index();
            $table->date('schedule_date')->nullable();
            $table->string('mro_data', length:400)->nullable();
            $table->foreignId('invoice_id')->nullable()->index()->constrained(table:'bill_invoice')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_mro_status')->noActionOnUpdate()->noActionOnDelete();
            $table->timestamps();
        });

        Schema::create('bill_mro_data_history', function(Blueprint $table){
            $table->id();
            $table->foreignId('mro_data_id')->nullable()->index()->constrained(table:'bill_mro_data')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_mro_status')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:400)->nullable();
            $table->dateTime('created_at')->nullable();
        });

        Schema::create('bill_recharges', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumer')->noActionOnUpdate()->noActionOnDelete();
            $table->string('transaction_id')->nullable();
            $table->double('amount')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_mro_status');
        Schema::dropIfExists('bill_mro_data');
        Schema::dropIfExists('bill_mro_data_history');
        Schema::dropIfExists('bill_recharges');
    }
};
