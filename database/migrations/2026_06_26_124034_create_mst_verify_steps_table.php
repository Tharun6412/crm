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
        /**
         * mst_verify_steps
         */
        Schema::create('mst_verify_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name',length:225)->nullable();
            $table->string('description',length:255)->nullable();
            $table->timestamps();
        });
        /**
         * verify_consumers
         */
        Schema::create('vfy_consumers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->nullable()->index()->constrained(table:'cns_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status');
            $table->string('remarks',length:255)->nullable();
            $table->string('updated_remarks',length:255)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        /**
         * Verify_consumer_steps
         */
        Schema::create('vfy_consumer_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verify_consumer_id')->nullable()->index()->constrained(table:'vfy_consumers')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('verify_step_id')->nullable()->index()->constrained(table:'mst_verify_steps')->noActionOnDelete()->noActionOnUpdate();
            $table->tinyInteger('status');
            $table->string('remarks',length:255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vfy_consumer_steps');
        Schema::dropIfExists('vfy_consumers');
        Schema::dropIfExists('mst_verify_steps');
    }
};
