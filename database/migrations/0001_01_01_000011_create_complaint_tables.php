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
        //cmp complaints
        Schema::create('cmp_complaints', function(Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->index()->nullable()->constrained(table:'cns_consumers')->noActionOnUpdate()->noActionOnDelete();
            $table->string('code', length:16)->nullable();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->string('name', length:60)->nullable();
            $table->string('email', length:60)->nullable();
            $table->string('phone', length:16)->nullable();
            $table->foreignId('category_id')->index()->nullable()->constrained(table:'mst_cmp_categories')->noActionOnUpdate()->noActionOnDelete();
            $table->string('description', length:225)->nullable();
            $table->foreignId('segment_id')->index()->nullable()->constrained(table:'mst_cmp_segments')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('type_id')->index()->nullable()->constrained(table:'mst_cmp_types')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('media_id')->index()->nullable()->constrained(table:'mst_cmp_media')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('priority_id')->index()->nullable()->constrained(table:'mst_cmp_priorities')->noActionOnUpdate()->noActionOnDelete();
            $table->dateTime('estimated_closed_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_cmp_status')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        //cmp complaint types
        Schema::create('cmp_complaint_assigns', function(Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->index()->nullable()->constrained(table:'cmp_complaints')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('assigned_to')->index()->nullable()->constrained(table:'users')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        //cmp complaint status
        Schema::create('cmp_complaint_status', function(Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->index()->nullable()->constrained(table:'cmp_complaints')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('status_id')->index()->nullable()->constrained(table:'mst_cmp_status')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        //cmp complaint documents
        Schema::create('cmp_complaint_documents', function(Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->index()->nullable()->constrained(table:'cmp_complaints')->noActionOnUpdate()->noActionOnDelete();
            $table->foreignId('file_id')->index()->nullable()->constrained(table:'dc_files')->noActionOnUpdate()->noActionOnDelete();
            $table->timestamps();
        });

        //cmp complaint comments
        Schema::create('cmp_complaint_comments', function(Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->index()->nullable()->constrained(table:'cmp_complaints')->noActionOnUpdate()->noActionOnDelete();
            $table->string('comments', length:225)->nullable();
            $table->morphs('commentable');
            $table->timestamps();
        });

        //cmp complaint comments
        Schema::create('cmp_complaint_feedback', function(Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->index()->nullable()->constrained(table:'cmp_complaints')->noActionOnUpdate()->noActionOnDelete();
            $table->decimal('rating', total:8, places:3);
            $table->string('notes', length:225)->nullable();
            $table->string('link', length:120)->nullable();
            $table->morphs('collectable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('cmp_complaint_feedback');
        Schema::dropIfExists('cmp_complaint_comments');
        Schema::dropIfExists('cmp_complaint_documents');
        Schema::dropIfExists('cmp_complaint_status');
        Schema::dropIfExists('cmp_complaint_assigns');
        Schema::dropIfExists('cmp_complaints');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};