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
        // Pipe sizes
        Schema::create('pms_pipes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->nullable();
            $table->string('name', length: 60)->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
        });

        // Network tiers
        Schema::create('pms_network_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 16)->nullable();
            $table->string('name', length: 60)->nullable();
            $table->string('description')->nullable();
            $table->foreignId('pipe_id')->nullable()->index()->constrained(table: 'pms_pipes')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
         
        // Network areas
        Schema::create('pms_network_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('network_tier_id')->nullable()->index()->constrained(table:'pms_network_tiers')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('street_name', length: 120)->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        /**
         * Lead Management System
         */
        // Lead status
        Schema::create('lms_lead_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 90)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('lms_lead_status')->onDelete(null);
            $table->boolean('status')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();
        });

        // Lead channels
        Schema::create('lms_lead_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 90)->nullable();
            $table->timestamps();
        });

        // Leads
        Schema::create('lms_leads', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 16)->nullable();
            $table->string('name', length: 90)->nullable();
            $table->string('mobile', length: 16)->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('owner_ship')->nullable();
            $table->string('lpg_service')->nullable();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'mst_cas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('newtwork_area_id')->nullable()->index()->constrained(table:'pms_network_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'lms_lead_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Lead documents
        Schema::create('lms_lead_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->index()->constrained(table:'lms_leads')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('file_type_id')->nullable()->index()->constrained(table:'dc_file_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('file_id')->nullable()->index()->constrained(table:'dc_files')->noActionOnDelete()->noActionOnUpdate();
        });

        // Lead Activities
        Schema::create('lms_lead_activities', function(Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->index()->constrained(table:'lms_leads')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('lead_channel_id')->nullable()->index()->constrained(table:'lms_lead_channels')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('lead_status_id')->nullable()->index()->constrained(table:'lms_lead_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_lead_activities');
        Schema::dropIfExists('lms_lead_documents');
        Schema::dropIfExists('lms_leads');
        Schema::dropIfExists('lms_lead_channels');
        Schema::dropIfExists('lms_lead_status');
        Schema::dropIfExists('pms_network_areas');
        Schema::dropIfExists('pms_network_tiers');
        Schema::dropIfExists('pms_pipes');
    }
};
