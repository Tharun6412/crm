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
        // cns_pngrb_applications
        Schema::create('cns_pngrb_applications', function(Blueprint $table) {
            $table->id();
            $table->string('applicationNumber')->nullable();
            $table->string('cgdId')->nullable();
            $table->string('gaId')->nullable();
            $table->string('status')->nullable();
            $table->string('ekycStatus')->nullable();
            $table->string('serviceabilityStatus')->nullable();
            $table->string('name')->nullable();
            $table->string('mobileNumber')->nullable();
            $table->string('father')->nullable();
            $table->string('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('houseNo')->nullable();
            $table->string('floor')->nullable();
            $table->string('society')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('premiseType')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('response_code')->nullable();
            $table->string('response_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cns_pngrb_applications');
    }
};
