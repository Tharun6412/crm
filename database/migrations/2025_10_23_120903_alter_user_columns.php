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
        //
        // Alter GA table with cluster_id
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
        //
    }
};
