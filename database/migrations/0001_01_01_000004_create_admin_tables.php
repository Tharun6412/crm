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
        Schema::create('adm_segments', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:50)->nullable();
        });

        // Cluster
        Schema::create('adm_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('code', length:16)->nullable();
            $table->string('name', length:225)->nullable();
            $table->string('description', length:120)->nullable();
            $table->tinyInteger('status')->nullable();
        });

        // State
        Schema::create('adm_states', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:100)->nullable();
            $table->tinyInteger('status')->nullable();
        });

        // GA
        Schema::create('adm_ga', function (Blueprint $table) {
            $table->id();
            $table->string('code', length:50)->nullable();
            $table->string('name', length:225)->nullable();
            $table->string('hes_code', length:20)->nullable();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'adm_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'adm_clusters')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('status')->nullable();
            $table->integer('position')->nullable();
            $table->dateTime('created_at')->nullable();
        });

        // District
        Schema::create('adm_districts', function (Blueprint $table) {
            $table->id();
            $table->string('code', length:16)->nullable();
            $table->string('name', length:225)->nullable();
            $table->string('display_name', length:225)->nullable();
            $table->foreignId('cluster_id')->nullable()->index()->constrained(table:'adm_clusters')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'adm_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
        });

        // CA
        Schema::create('adm_ca', function(Blueprint $table) {
            $table->id();
            $table->string('code', length:120)->nullable();
            $table->string('name', length:120)->nullable();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'adm_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });

        // Areas
        Schema::create('adm_areas', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:120)->nullable();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'adm_ca')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });

        // Departments
        Schema::create('adm_departments', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:225)->nullable();
            $table->timestamps();
        });

        // User Ga
        Schema::create('adm_user_ga', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained(table: 'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
        });

        // Alter User table department and ga_id
        Schema::table('users', function(Blueprint $table) {
            $table->foreignId('ga_id')->after('status')->nullable()->index()->constrained(table:'adm_ga')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('department_id')->after('ga_id')->nullable()->index()->constrained(table:'adm_departments')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('adm_departments');
        Schema::dropIfExists('adm_user_ga');
        Schema::dropIfExists('adm_areas');
        Schema::dropIfExists('adm_ca');
        Schema::dropIfExists('adm_districts');
        Schema::dropIfExists('adm_ga');
        Schema::dropIfExists('adm_state');
        Schema::dropIfExists('adm_clusters');
        Schema::dropIfExists('adm_segments');
    }
};
