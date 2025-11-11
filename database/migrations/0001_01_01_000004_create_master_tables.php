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
        // Segments
        Schema::create('mst_segments', function(Blueprint $table) {
            $table->id();
            $table->string('code', length: 4)->nullable();
            $table->string('name', length: 20)->nullable();
        });

        // Cluster
        Schema::create('mst_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 16)->nullable();
            $table->string('name', length: 60)->nullable();
            $table->string('description', length:180)->nullable();
            $table->boolean('display_status')->nullable();
            $table->boolean('status')->nullable();
        });
        
        // State
        Schema::create('mst_states', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 8)->nullable();
            $table->string('name', length: 90)->nullable();
            $table->tinyInteger('status')->nullable();
        });

        // GA
        Schema::create('mst_gas', function (Blueprint $table) {
            $table->id();
            $table->string('code', length: 50)->nullable();
            $table->string('name', length: 225)->nullable();
            $table->string('hes_code', length: 20)->nullable();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'mst_clusters')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('status')->nullable();
            $table->integer('position')->nullable();
            $table->dateTime('created_at')->nullable();
        });

        // District
        Schema::create('mst_districts', function (Blueprint $table) {
            $table->id();
            $table->string('code', length:16)->nullable();
            $table->string('name', length:225)->nullable();
            $table->string('display_name', length:225)->nullable();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'mst_clusters')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });

        // CA
        Schema::create('mst_cas', function(Blueprint $table) {
            $table->id();
            $table->string('code', length:120)->nullable();
            $table->string('name', length:120)->nullable();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });

        // Areas
        Schema::create('mst_areas', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:120)->nullable();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'mst_cas')->noActionOnDelete()->noActionOnUpdate();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });

        // Departments
        Schema::create('mst_departments', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });

        // User Ga
        Schema::create('adm_user_ga', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table: 'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
        });

        // User status history
        Schema::create('adm_user_status', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table: 'users')->noActionOnDelete()->noActionOnUpdate();
            $table->boolean('status')->nullable();
            $table->string('notes', length:225)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // Alter User table department and ga_id
        Schema::table('users', function(Blueprint $table) {
            $table->foreignId('ga_id')->after('status')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('department_id')->after('ga_id')->nullable()->index()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_departments');
        Schema::dropIfExists('adm_user_status');
        Schema::dropIfExists('adm_user_ga');
        Schema::dropIfExists('mst_areas');
        Schema::dropIfExists('mst_ca');
        Schema::dropIfExists('mst_districts');
        Schema::dropIfExists('mst_gas');
        Schema::dropIfExists('mst_state');
        Schema::dropIfExists('mst_clusters');
        Schema::dropIfExists('mst_segments');
    }
};
