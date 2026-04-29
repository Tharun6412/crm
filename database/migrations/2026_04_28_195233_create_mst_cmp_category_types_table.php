<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * mst_cmp_category_types table
     */
    public function up(): void
    {
        Schema::create('mst_cmp_category_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:100)->nullable();
            $table->timestamps();
        });

        //added priority_id column
        Schema::table('mst_cmp_categories', function (Blueprint $table) {
            $table->foreignId('priority_id')->nullable()->index()->after('type_id')->constrained(table:'mst_cmp_priorities')->noActionOnDelete()->noActionOnUpdate();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_cmp_category_types');
    }
};
