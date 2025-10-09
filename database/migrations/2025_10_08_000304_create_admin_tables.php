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
        // Cluster
        Schema::create('adm_cluster', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
        });
        // State
        Schema::create('adm_state', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:100)->nullable();
            $table->string('coordinates', length:225)->nullable();
            $table->string('gst', length:225)->nullable();
            $table->integer('tin')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // GA
        Schema::create('adm_ga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'adm_state')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'adm_cluster')->noActionOnDelete()->noActionOnUpdate();
            $table->string('code', length:50)->nullable();
            $table->string('hes_code', length:20)->nullable();
            $table->string('code_backup', length:20)->nullable();
            $table->string('name', length:225)->nullable();
            $table->integer('cng_counter')->nullable();
            $table->integer('status')->nullable();
            $table->integer('priority')->nullable();
            $table->dateTime('added_at')->nullable();
            $table->foreignId('added_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // District
        Schema::create('adm_district', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'adm_cluster')->noActionOnDelete()->noActionOnUpdate();
            $table->string('name', length:225)->nullable();
            $table->string('ccavenue_name_meil', length:60)->nullable();
            $table->string('display_name', length:225)->nullable();
            $table->integer('code')->nullable();
            $table->foreignId('state')->nullable()->index()->constrained(table:'adm_state')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('geo_area')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('status')->nullable();
            $table->string('contact_no', length:100)->nullable();
            $table->string('coordinates', length:225)->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // PNG Firm Types Alias Segments in leads
        Schema::create('adm_png_firm_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // PNG Fuel Types
        Schema::create('adm_png_fuel_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->integer('position')->nullable();
            $table->tinyInteger('spot')->nullable();
            $table->integer('fuel_group')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('status')->nullable();
        });
        // Lead Industrial Areas
        Schema::create('adm_industrial_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_industrial_areas');
        Schema::dropIfExists('adm_png_fuel_types');
        Schema::dropIfExists('adm_png_firm_types');
        Schema::dropIfExists('adm_district');
        Schema::dropIfExists('adm_ga');
        Schema::dropIfExists('adm_state');
        Schema::dropIfExists('adm_cluster');
    }
};
