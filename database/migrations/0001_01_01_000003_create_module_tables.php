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
        // Modules
        Schema::create('adm_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:90)->nullable();
            $table->string('slug', length:60)->nullable();
            $table->string('url', length:180)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('adm_modules')->onDelete(null);
            $table->integer('position')->nullable();
            $table->timestamps();
        });
        
        // Module URLs
        Schema::create('adm_module_urls', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:90)->nullable();
            $table->foreignId('module_id')->nullable()->index()->constrained(table:'adm_modules')->noActionOnDelete()->noActionOnUpdate();
            $table->string('url', length:180)->nullable();
        });
        
        // Module actions
        Schema::create('adm_module_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->index()->constrained(table:'adm_modules')->noActionOnDelete()->noActionOnUpdate();
            $table->string('action', length:90)->nullable();
            $table->string('slug', length:60)->nullable();
        });
        
        // Roles
        Schema::create('adm_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:90)->nullable();
            $table->timestamps();
        });
        
        // Role actions
        Schema::create('adm_role_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->index()->constrained(table:'adm_roles')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('module_action_id')->nullable()->index()->constrained(table:'adm_module_actions')->noActionOnDelete()->noActionOnUpdate();
        });

        // User roles
        Schema::create('adm_user_roles', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table: 'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('role_id')->nullable()->index()->constrained(table: 'adm_roles')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_role_actions');
        Schema::dropIfExists('adm_roles');
        Schema::dropIfExists('adm_module_actions');
        Schema::dropIfExists('adm_module_urls');
        Schema::dropIfExists('adm_modules');
    }
};
