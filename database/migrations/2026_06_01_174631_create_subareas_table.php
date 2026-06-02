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
        Schema::create('mst_sub_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:225)->nullable();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        Schema::create('mst_areas_tfhh',function(Blueprint $table) {
            $table->id();
            $table->foreignId('ca_id')->nullable()->index()->constrained(table:'mst_cas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('area_id')->nullable()->index()->constrained(table:'mst_areas')->noActionOnDelete()->noActionOnUpdate();
            $table->integer('count')->nullable();
            $table->timestamps();
        });

        Schema::table('cns_consumers',function(Blueprint $table) {
            $table->foreignId('subarea_id')->nullable()->index()->after('ward')->constrained('mst_sub_areas')->noActionOnDelete()->noActionOnUpdate();
        });

        Schema::table('adm_teams',function(Blueprint $table) {
            $table->foreignId('responsible_user_id')->nullable()->index()->after('status')->constrained('users')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_areas_tfhh');
        Schema::dropIfExists('mst_sub_areas');
    }
};
