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
        // Create
        Schema::create('adm_user_exports', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->string('file_name', length:60)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('adm_user_exports');
    }
};
