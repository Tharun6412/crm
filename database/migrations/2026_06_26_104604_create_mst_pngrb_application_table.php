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
        /**
         * Application status - master data
         */
        Schema::create('mst_pngrb_application_status', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        /**
         * Application status history
         */
        Schema::create('cns_pngrb_application_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pngrb_id')->nullable()->index()->constrained(table:'cns_pngrb_applications')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_pngrb_application_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        /**
         * Application files
         */
        Schema::create('cns_pngrb_application_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->index()->constrained(table:'cns_pngrb_applications')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('file_id')->nullable()->index()->constrained(table:'dc_files')->noActionOnDelete()->noActionOnUpdate();
            $table->string('type', length: 64)->nullable();
            $table->string('category', length: 64)->nullable();
            $table->timestamp('created_at');
        });

        /**
         * Application complaints
         */
        Schema::create('cns_pngrb_application_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->index()->constrained(table:'cns_pngrb_applications')->noActionOnDelete()->noActionOnUpdate();
            $table->string('complaint_id', length: 32)->nullable();
            $table->string('central_complaint_id', length: 64)->nullable();
            $table->string('category', length: 255)->nullable();
            $table->string('sub_category', length: 255)->nullable();
            $table->text('description')->nullable();
            $table->text('attachments')->nullable();
            $table->foreignId('complaint_id')->nullable()->index()->constrained(table:'cmp_complaints')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cns_pngrb_application_files');
        Schema::dropIfExists('mst_pngrb_application_status');
        Schema::dropIfExists('cns_pngrb_application_status');
    }
};
