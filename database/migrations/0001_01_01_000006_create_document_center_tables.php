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
        // Document types
        Schema::create('dc_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 90);
            $table->timestamps();
        });
        // Documents
        Schema::create('dc_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dc_type_id')->index()->nullable()->constrained(table: 'dc_types')->noActionOnUpdate()->noActionOnDelete();
            $table->string('doc_number', length: 32)->nullable();
            $table->string('disk', length: 16)->nullable();
            $table->string('file_name_original', length: 225)->nullable();
            $table->string('file_path', length: 225)->nullable();
            $table->string('url', length: 225)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('tag', length:50)->nullable();
            $table->string('description', length:225)->nullable();
            $table->foreignId('created_by')->index()->nullable()->constrained('users')->noActionOnUpdate()->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('dc_files');
    }
};
