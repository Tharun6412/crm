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
        Schema::create('mst_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 90)->nullable();
            $table->foreignId('ga_id')->index()->nullable()->constrained(table: 'mst_gas')->noActionOnUpdate()->noActionOnDelete();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });

        // Zone areas
        Schema::create('mst_zone_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->index()->nullable()->constrained(table: 'mst_zones')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('area_id')->index()->nullable()->constrained(table: 'mst_areas')->noActionOnUpdate()->noActionOnDelete();
        });

        // Billing cycles
        Schema::create('mst_bill_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 90)->nullable();
            $table->string('code', length: 16)->nullable();
            $table->tinyInteger('position')->nullable();
            $table->tinyInteger('start_day')->nullable();
            $table->tinyInteger('end_day')->nullable();
            $table->tinyInteger('days')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Billing cycle zones
        Schema::create('mst_bill_cycle_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->index()->nullable()->constrained(table: 'mst_bill_cycles')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('zone_id')->index()->nullable()->constrained(table: 'mst_zones')->noActionOnUpdate()->noActionOnDelete();
            $table->timestamps();
        });
        
        // Bill periods
        Schema::create('bil_periods', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 32)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });

        // Bill schedules
        Schema::create('bil_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 32)->nullable();
            $table->foreignId('period_id')->index()->nullable()->constrained(table: 'bil_periods')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('cycle_id')->index()->nullable()->constrained(table: 'mst_bill_cycles')->noActionOnUpdate()->noActionOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // Bill scheduled consumers
        Schema::create('bil_schedule_consumers', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 32)->nullable();
            $table->foreignId('schedule_id')->index()->nullable()->constrained(table: 'bil_schedules')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table: 'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->dateTime('billed_date')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bil_schedule_consumers');
        Schema::dropIfExists('bil_schedules');
        Schema::dropIfExists('bil_periods');
        Schema::dropIfExists('mst_bill_cycle_zones');
        Schema::dropIfExists('mst_bill_cycles');
        Schema::dropIfExists('mst_zone_areas');
        Schema::dropIfExists('mst_zones');
    }
};
