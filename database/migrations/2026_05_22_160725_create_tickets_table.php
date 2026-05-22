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
        //mst_tkt_categories
        Schema::create('mst_tkt_categories', function(Blueprint $table) {
            $table->id();
            $table->string('name',length:225)->nullable();
            $table->foreignId('department_id')->nullable()->index()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        //mst_tkt_status
        Schema::create('mst_tkt_status', function(Blueprint $table) {
            $table->id();
            $table->string('name',length:255)->nullable();
        });

        //tkt_tickets
        Schema::create('tkt_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code',length:16)->nullable();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('category_id')->nullable()->index()->constrained(table:'mst_tkt_categories')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_tkt_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('description',length:255)->nullable();
             $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        //tkt_status
        Schema::create('tkt_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->index()->constrained(table:'tkt_tickets')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('status_id')->nullable()->index()->constrained(table:'mst_tkt_status')->noActionOnDelete()->noActionOnUpdate();
            $table->string('notes',length:225)->nullable();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_status');
        Schema::dropIfExists('tkt_tickets');
        Schema::dropIfExists('mst_tkt_status');
        Schema::dropIfExists('mst_tkt_categories');
    }
};
