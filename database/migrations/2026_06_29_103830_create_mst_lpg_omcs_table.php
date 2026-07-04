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
        Schema::create('mst_lpg_omcs', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:225)->nullable();
            $table->timestamps();
        });
        
        //add lpg details columns in consumer data
        Schema::table('cns_consumer_data',function(Blueprint $table) {
            $table->string('lpg_consumer_number')->nullable()->after('lng');
            $table->string('lpg_id')->nullable()->after('lpg_consumer_number');
            $table->foreignId('lpg_omc_id')->nullable()->index()->after('lpg_id')->constrained(table:'mst_lpg_omcs')->noActionOnDelete()->noActionOnUpdate();
            $table->string('registered_mobile',length:15)->nullable()->after('lpg_omc_id');
            $table->string('lpg_connections')->nullable()->after('registered_mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_lpg_omcs');
    }
};
