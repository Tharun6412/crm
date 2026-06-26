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
        Schema::create('mst_pngrb_application_status', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('cns_pngrb_application_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pngrb_id')->nullable()->index()->constrained(table:'cns_pngrb_applications')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_pngrb_application_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_pngrb_application_status');
        Schema::dropIfExists('cns_pngrb_application_status');
    }
};
