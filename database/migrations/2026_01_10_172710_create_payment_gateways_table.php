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
         * Payment gateways
         */
        Schema::create('mst_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway'); // easebuzz, razorpay, stripe, payu
            $table->enum('mode', ['test', 'production']);
            $table->json('credentials'); // encrypted
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
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->string('sub_merchant_id', length: 32)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_payment_gateways');
        Schema::dropIfExists('mst_payment_gateway_details');
    }
};
