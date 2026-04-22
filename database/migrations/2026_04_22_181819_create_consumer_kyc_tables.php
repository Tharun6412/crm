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
        // Zones
        Schema::create('cns_consumer_kyc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table: 'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('title')->index()->nullable()->constrained(table: 'mst_titles')->noActionOnUpdate()->noActionOnDelete();
            $table->string('fname', length: 60)->nullable();
            $table->string('lname', length: 60)->nullable();
            $table->foreignId('cof')->nullable()->index()->constrained(table:'mst_titles')->noActionOnDelete()->noActionOnUpdate();
            $table->string('cof_name', length: 90)->nullable();
            $table->string('aadhar', length: 16)->nullable();
            $table->string('phone', length: 16)->nullable();
            $table->string('phone_alt', length: 16)->nullable();
            $table->string('email', length: 90)->nullable();
            $table->string('nominee', length: 90)->nullable();
            $table->foreignId('nominee_relation_id')->nullable()->index()->constrained(table:'mst_cns_nominee_relations')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Update Consumer Data
        Schema::table('cns_consumer_data', function (Blueprint $table) {
            $table->tinyInteger('kyc_status')->nullable()->after('lng');
        });

        // Update Bill Invoice Consumption
        Schema::table('bil_invoice_consumption', function (Blueprint $table) {
            $table->string('lat', length:60)->nullable()->after('file_id');
            $table->string('lng', length:60)->nullable()->after('lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cns_consumer_kyc');
    }
};
