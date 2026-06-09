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
        Schema::create('cns_referral_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->string('name',length:255)->nullable();
            $table->string('phone', length:16)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->date('reedem_date')->nullable();
            $table->decimal('reedem_amount', 8, 3)->nullable();
            $table->foreignId('referral_consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        Schema::table('cns_consumer_data',function(Blueprint $table) {
            $table->string('reference_code')->nullable()->after('consumer_id');
            $table->string('referrer_code')->nullable()->after('reference_code');
            $table->foreignId('referrer_consumer_id')->nullable()->index()->after('referrer_code')->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cns_referral_requests');
    }
};
