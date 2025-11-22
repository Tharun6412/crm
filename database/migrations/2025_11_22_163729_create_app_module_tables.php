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
         * App modules
         */
        Schema::create('adm_app_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 102)->nullable();
            $table->string('code', length:8)->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });

        /**
         * App module roles
         */
        Schema::create('adm_role_app_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->index()->constrained(table:'adm_roles')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('app_module_id')->nullable()->index()->constrained(table:'adm_app_modules')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_role_app_modules');
        Schema::dropIfExists('adm_app_modules');
    }
};
