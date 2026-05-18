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
        //adm_teams
        Schema::create('adm_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:255)->nullable();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('department_id')->nullable()->index()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status')->default(1);
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        //adm_team_users
        Schema::create('adm_team_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullable()->index()->constrained(table:'adm_teams')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('user_id')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        //adm_team_cas table
        Schema::create('adm_team_cas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullable()->index()->constrained(table:'adm_teams')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'mst_cas')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        //adm_user_types table
        Schema::create('adm_user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:100)->nullable();
        });
        //added column in users
         Schema::table('users', function (Blueprint $table) {
            $table->foreignId('type_id')->nullable()->index()->after('type')->constrained(table:'adm_user_types')->noActionOnDelete()->noActionOnUpdate();
        });
        // added column in lms leads table
         Schema::table('lms_leads', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->index()->after('ga_id')->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
        });
        // added column in lms leads table
         Schema::table('lms_leads', function (Blueprint $table) {
            $table->foreignId('lead_channel_id')->nullable()->index()->after('area_id')->constrained(table:'lms_lead_channels')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   Schema::dropIfExists('adm_user_types');
        Schema::dropIfExists('adm_team_cas');
        Schema::dropIfExists('adm_team_users');
        Schema::dropIfExists('adm_teams');  
    }
};
