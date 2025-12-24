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
        //cmp_complaints Table Columns update
        Schema::table('cmp_complaints', function(Blueprint $table) {
            $table->foreignId('state_id')->nullable()->index()->after('code')->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->after('state_id')->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->after('ga_id')->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
        });

        //cmp_complaint_comments Table Columns update
        Schema::table('cmp_complaint_comments', function(Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->morphs('commentable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //cmp_complaints
         Schema::table('cmp_complaints', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['state_id']);
            $table->dropForeign(['ga_id']);
            $table->dropForeign(['district_id']);
            // Drop columns
            $table->dropColumn(['state_id', 'ga_id', 'district_id']);
        });
    }
};
