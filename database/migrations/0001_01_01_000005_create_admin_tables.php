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
        // User Ga
        Schema::create('adm_user_ga', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table: 'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
        });

        // User status
        Schema::create('adm_user_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:64)->nullable();
        });

        // User status history
        Schema::create('adm_user_status_history', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table: 'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'adm_user_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Alter User table department and ga_id
        Schema::table('users', function(Blueprint $table) {
            $table->foreignId('status_id')->after('type')->nullable()->index()->constrained(table:'adm_user_status')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('department_id')->after('status_id')->nullable()->index()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->after('department_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('mst_departments');
        Schema::dropIfExists('adm_user_status_history');
        Schema::dropIfExists('adm_user_status');
        Schema::dropIfExists('adm_user_ga');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
