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
         * Payment gateway details - sub merchants
         */
        Schema::create('mst_payment_gateway_details', function(Blueprint $table) {
            $table->id();
            $table->foreignId('payment_gateway_id')->nullable()->index()->constrained(table:'mst_payment_gateways')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('sub_merchant_id', length: 32)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('mst_payment_gateway_details');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
