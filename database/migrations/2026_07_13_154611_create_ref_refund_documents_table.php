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
        Schema::create('ref_refund_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->nullable()->index()->constrained(table:'ref_refunds')->noActionOnDelete()->noActionOnDelete();
            $table->foreignId('file_id')->index()->nullable()->constrained(table:'dc_files')->noActionOnUpdate()->noActionOnDelete();
            $table->string('notes',length:255)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_refund_documents');
    }
};
