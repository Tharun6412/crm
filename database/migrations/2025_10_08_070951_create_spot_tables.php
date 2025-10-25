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
        // Spot Roles
        Schema::create('spot_roles', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->timestamps();
        });
        // Spot Status
        Schema::create('spot_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->integer('type')->nullable();
            $table->integer('parent')->nullable();
        });
        // Spot Table
        Schema::create('spot_prospects', function (Blueprint $table) {
            $table->id();
            $table->string('code', length:50)->nullable();
            $table->string('name', length:225)->nullable();
            $table->foreignId('firm_id')->nullable()->index()->constrained(table:'adm_png_firm_types')->noActionOnDelete()->noActionOnUpdate(); // COMMERCIAL DOMESTIC
            $table->foreignId('fuel_id')->nullable()->index()->constrained(table:'adm_png_fuel_types')->noActionOnDelete()->noActionOnUpdate();
            $table->string('fuel_consumption', length:225)->nullable();
            $table->integer('unit_id')->nullable();
            $table->double('potential')->nullable();
            $table->date('expected_date')->nullable();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'adm_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'adm_clusters')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('industrial_area_id')->nullable()->index()->constrained(table:'adm_industrial_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('zone', length:225)->nullable();
            $table->string('latitude', length:225)->nullable();
            $table->string('longitude', length:225)->nullable();
            $table->foreignId('ga_head')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_head')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('segment_id')->nullable()->index(); 
            $table->tinyInteger('pipeline_availability')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('stage')->nullable()->index()->constrained(table:'spot_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('sub_stage_id')->nullable()->index()->constrained(table:'spot_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'spot_status')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('status_date')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Spot Approval
        Schema::create('spot_prospect_approval', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'spot_status')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('created_at')->nullable();
        });
        // Spot Comments
        Schema::create('spot_prospect_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->text('comments')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('created_at')->nullable();
        });
        // Spot Date Change History
        Schema::create('spot_prospect_date_change_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('current_date')->nullable();
            $table->dateTime('new_date')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('approved_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('approved_at')->nullable();
        });
        // Spot Document Types
        Schema::create('spot_document_types', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // Spot Documents
        Schema::create('spot_prospect_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('document_type_id')->nullable()->index()->constrained(table:'spot_document_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('doc_file_id')->nullable()->index()->constrained(table:'dc_files')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('offer_count')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('win')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // Spot Pipeline
        Schema::create('spot_prospect_pipeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('pipe_type')->nullable();
            $table->double('length')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // Spot Status History
        Schema::create('spot_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'spot_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('sub_status_id')->nullable()->index()->constrained(table:'spot_status')->noActionOnDelete()->noActionOnUpdate();
            $table->text('notes')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // Spot Targets
        Schema::create('spot_prospect_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->date('target_date')->nullable();
            $table->tinyInteger('segment_id')->nullable();
            $table->integer('target_quantity')->nullable();
            $table->double('target_value')->nullable();
        });
        // Prospects and Users
        Schema::create('spot_prospect_users', function(Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spot_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('role_id')->nullable()->index()->constrained(table:'spot_roles')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('user_id')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_prospect_users');
        Schema::dropIfExists('spot_prospect_targets');
        Schema::dropIfExists('spot_status_history');
        Schema::dropIfExists('spot_prospect_pipeline');
        Schema::dropIfExists('spot_prospect_documents');
        Schema::dropIfExists('spot_prospect_document_types');
        Schema::dropIfExists('spot_prospect_date_change_history');
        Schema::dropIfExists('spot_prospect_comments');
        Schema::dropIfExists('spot_prospect_approval');
        Schema::dropIfExists('spot_prospects');
        Schema::dropIfExists('spot_prospect_status');
        Schema::dropIfExists('spot_propect_roles');
    }
};
