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
        Schema::create('mst_bill_collection_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ga_id')->nullable()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('timings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_bill_collection_centers');
    }
};
