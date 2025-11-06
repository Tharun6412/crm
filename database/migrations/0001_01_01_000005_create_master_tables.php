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
            $table->string('name', length:225)->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_png_firm_types');
        Schema::dropIfExists('adm_png_fuel_types');
        Schema::dropIfExists('adm_industrial_areas');
    }
};