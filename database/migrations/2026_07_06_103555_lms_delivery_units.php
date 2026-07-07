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
        //Delivery Unit
        Schema::create('lms_delivery_units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('manager_id')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('dept_id')->nullable()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('responsible_status_id')->nullable()->index()->constrained(table:'mst_cns_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('action_status_id')->nullable()->index()->constrained(table:'mst_cns_status')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Lms Delivery Areas
        Schema::create('lms_du_areas', function(Blueprint $table) {
            $table->id();
            $table->foreignId('du_id')->nullable()->index()->constrained(table:'lms_delivery_units')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('dept_id')->nullable()->index()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
        });

        // Lms Team Areas
        Schema::create('lms_team_areas', function(Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullable()->index()->constrained(table:'lms_teams')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
        });

        // Lms Teams
        Schema::table('lms_teams', function(Blueprint $table) {
            $table->foreignId('du_id')->nullable()->index()->after('department_id')->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('lms_team_areas');
        Schema::dropIfExists('lms_du_areas');
        Schema::dropIfExists('lms_delivery_units');
    }
};
