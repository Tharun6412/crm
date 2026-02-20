<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // spt Status
        Schema::create('spt_stages', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->integer('type')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('spt_stages')->nullOnDelete();
            $table->integer('position')->nullable();
        });

        // spt Status
        Schema::create('spt_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->timestamps();
        });

        // spt prospects Table
        Schema::create('spt_prospects', function (Blueprint $table) {
            $table->id();
            $table->string('code', length:50)->nullable();
            $table->string('name', length:225)->nullable();
            $table->foreignId('firm_id')->nullable()->index()->constrained(table:'mst_firm_types')->noActionOnDelete()->noActionOnUpdate(); // COMMERCIAL DOMESTIC
            $table->foreignId('fuel_id')->nullable()->index()->constrained(table:'mst_fuel_types')->noActionOnDelete()->noActionOnUpdate();
            $table->string('fuel_consumption', length:225)->nullable();
            $table->integer('unit_id')->nullable();
            $table->double('potential')->nullable();
            $table->date('expected_date')->nullable();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'mst_clusters')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('industrial_area_id')->nullable()->index()->constrained(table:'mst_industrial_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('zone', length:225)->nullable();
            $table->string('latitude', length:225)->nullable();
            $table->string('longitude', length:225)->nullable();
            $table->foreignId('ga_head')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_head')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('segment_id')->nullable()->index(); 
            $table->tinyInteger('pipeline_availability')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('stage_id')->nullable()->index()->constrained(table:'spt_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'spt_status')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('status_date')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });

        // spt Approval
        Schema::create('spt_prospect_approval', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spt_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'spt_status')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('created_at')->nullable();
        });
        // spt Comments
        Schema::create('spt_prospect_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spt_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->text('comments')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('created_at')->nullable();
        });
        // spt Date Change History
        Schema::create('spt_prospect_date_change_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spt_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('current_date')->nullable();
            $table->dateTime('new_date')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('approved_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->dateTime('approved_at')->nullable();
        });
        // spt Documents
        Schema::create('spt_prospect_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spt_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('document_type_id')->nullable()->index()->constrained(table:'dc_file_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('doc_file_id')->nullable()->index()->constrained(table:'dc_files')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('offer_count')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('win')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // spt Pipeline
        Schema::create('spt_prospect_pipeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spt_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('pipe_type_id')->nullable()->index()->constrained(table:'pms_pipes')->noActionOnDelete()->noActionOnUpdate();
            $table->double('length')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->unique(['prospect_id', 'pipe_type_id', 'status']);
        });
        // spt Status History
        Schema::create('spt_prospect_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->nullable()->index()->constrained(table:'spt_prospects')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('stage_id')->nullable()->index()->constrained(table:'spt_status')->noActionOnDelete()->noActionOnUpdate();
            $table->text('notes')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });
        // spt Targets
        Schema::create('spt_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->date('target_date')->nullable();
            $table->tinyInteger('segment_id')->nullable();
            $table->integer('target_quantity')->nullable();
            $table->double('target_value')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->unique(['ga_id', 'target_date', 'segment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable Foreign Keys
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('spt_targets');
        Schema::dropIfExists('spt_prospect_status_history');
        Schema::dropIfExists('spt_prospect_pipeline');
        Schema::dropIfExists('spt_prospect_documents');
        Schema::dropIfExists('spt_prospect_date_change_history');
        Schema::dropIfExists('spt_prospect_comments');
        Schema::dropIfExists('spt_prospect_approval');
        Schema::dropIfExists('spt_prospects');
        Schema::dropIfExists('spt_prospect_status');
        Schema::dropIfExists('spt_status');
        Schema::dropIfExists('spt_roles');
        // Enable Foreign Keys
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
