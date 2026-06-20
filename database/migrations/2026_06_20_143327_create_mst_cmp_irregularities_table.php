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
        //mst_cmp_irregularities
        Schema::create('mst_cmp_irregularities', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:225)->nullable();
            $table->timestamps();
        });
        
        Schema::table('cmp_complaints',function (Blueprint $table) {
            $table->foreignId('irregularities_id')->nullable()->index()->after('media_id')->constrained('mst_cmp_irregularities')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_cmp_irregularities');
    }
};
