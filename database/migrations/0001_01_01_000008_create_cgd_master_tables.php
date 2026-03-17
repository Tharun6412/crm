<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        // mst_titles
        Schema::create('mst_titles', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:100)->nullable();
            $table->integer('type')->nullable();
            $table->timestamps();
        });

        // mst cns scheme payments
        Schema::create('mst_cns_scheme_payments', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:100)->nullable();
            $table->timestamps();
        });
        // mst cns schemes
        Schema::create('mst_cns_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->nullable()->index()->constrained(table:'mst_segments')->noActionOnDelete()->noActionOnUpdate();
            $table->string('code', length:16)->nullable();
            $table->string('name',length:100)->nullable();
            $table->double('registration')->nullable();
            $table->double('security')->nullable();
            $table->double('consumption')->nullable();
            $table->double('total_deposit')->nullable();
            $table->double('min_payment')->nullable();
            $table->double('emi_amount')->nullable();
            $table->double('rental_amount')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('scheme_payment_id')->nullable()->index()->constrained(table:'mst_cns_scheme_payments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        // mst cns scheme ga
        Schema::create('mst_cns_scheme_ga', function(Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->nullable()->index()->constrained(table:'mst_cns_schemes')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->unique(['scheme_id', 'ga_id']);
            $table->timestamps();
        });

        // Mst tax groups
        Schema::create('mst_tax_groups', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:32)->nullable();
        });

        // mst taxes
        Schema::create('mst_taxes', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:32)->nullable();
            $table->foreignId('tax_group_id')->nullable()->index()->constrained(table:'mst_tax_groups')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // mst price
        Schema::create('mst_price', function(Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->nullable()->index()->constrained(table:'mst_segments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->double('basic')->nullable();
            $table->double('supply')->nullable();
            $table->double('margin')->nullable();
            $table->double('basic_price')->nullable();
            $table->foreignId('tax_id')->nullable()->index()->constrained(table:'mst_taxes')->noActionOnDelete()->noActionOnUpdate();
            $table->double('tax_value')->nullable();
            $table->double('tax_price')->nullable();
            $table->double('rsp')->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // mst price history
        Schema::create('mst_price_history', function(Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->nullable()->index()->constrained(table:'mst_price')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('segment_id')->nullable()->index()->constrained(table:'mst_segments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->double('basic')->nullable();
            $table->double('supply')->nullable();
            $table->double('margin')->nullable();
            $table->double('basic_price')->nullable();
            $table->foreignId('tax_id')->nullable()->index()->constrained(table:'mst_taxes')->noActionOnDelete()->noActionOnUpdate();
            $table->double('tax_value')->nullable();
            $table->double('tax_price')->nullable();
            $table->double('rsp')->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // mst state vat
        Schema::create('mst_state_vat', function(Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->decimal('vat', total:8, places:3);
            $table->timestamps();
        });

        // mst cns nominee relations
        Schema::create('mst_cns_nominee_relations', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst cns gas required
        Schema::create('mst_cns_gas_required', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst cns status
        Schema::create('mst_cns_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->string('slug', length:60)->nullable();
            $table->timestamps();
        });
        // mst cns meter status
        Schema::create('mst_cns_meter_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst cns geyser status
        Schema::create('mst_cns_geyser_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst invoice bil item types
        Schema::create('mst_bil_invoice_item_types', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:100);
            $table->timestamps();
        });
        // mst invoice bil items
        Schema::create('mst_bil_invoice_items', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('type_id')->index()->nullable()->constrained(table:'mst_bil_invoice_item_types')->noActionOnDelete()->noActionOnUpdate();
            $table->string('code', length:16)->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('hsn', length:16)->nullable();
            $table->boolean('price_type')->nullable();
            $table->double('basic')->nullable();
            $table->decimal('tax_value', 4, 2)->nullable();
            $table->double('price')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        // mst bil address
        Schema::create('mst_bil_address', function(Blueprint $table) {
            $table->id();
            $table->foreignId('ga_id')->nullable()->index()->constrained(table:'mst_gas')->noActionOnDelete()->noActionOnUpdate();
            $table->string('line1', length:225)->nullable();
            $table->string('line2', length:225)->nullable();
            $table->string('city', length:225)->nullable();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('state_id')->nullable()->index()->constrained(table:'mst_states')->noActionOnDelete()->noActionOnUpdate();
            $table->string('pincode', length:10)->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });
        // mst bil status
        Schema::create('mst_bil_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst pay types
        Schema::create('mst_pay_types', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
        // mst pay status
        Schema::create('mst_pay_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst pay checque status
        Schema::create('mst_pay_cheque_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst pay pg data
        Schema::create('mst_pay_pg_data', function(Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->nullable()->index()->constrained(table:'mst_districts')->noActionOnDelete()->noActionOnUpdate();
            $table->string('sub_merchant_name', length:60)->nullable();
            $table->timestamps();
        });
        // mst ref status
        Schema::create('mst_ref_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });

        // mst cmp status
        Schema::create('mst_cmp_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });
        // mst cmp segments
        Schema::create('mst_cmp_segments', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable(); //DPNG, CPNG, CNG, IPNG, Other
            $table->timestamps();
        });
        // mst cmp types
        Schema::create('mst_cmp_types', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable(); //Enquiry | Request |Complaint
            $table->timestamps();
        });
        // mst cmp media
        Schema::create('mst_cmp_media', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable(); //Call | App | etc
            $table->timestamps();
        });
        // mst cmp priorities
        Schema::create('mst_cmp_priorities', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable(); //Low | Medium | High
            $table->timestamps();
        });
        
        // mst cmp Categories
        Schema::create('mst_cmp_categories', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->decimal('resolution', 4, 2)->nullable();
            $table->boolean('resolution_type')->nullable();
            $table->foreignId('type_id')->nullable()->index()->constrained(table:'mst_cmp_types')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('department_id')->nullable()->index()->constrained(table:'mst_departments')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('parent_id')->nullable()->constrained('mst_cmp_categories')->nullOnDelete();
            $table->integer('position')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained(table:'users')->noActionOnDelete()->noActionOnUpdate();
            $table->timestamps();
        });

        // mst sd payment status
        Schema::create('mst_sd_payment_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable(); //Low | Medium | High
            $table->timestamps();
        });
        // mst sd transaction status
        Schema::create('mst_sd_transaction_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable(); //Low | Medium | High
            $table->timestamps();
        });
        
        // mst bil invoice types
        Schema::create('mst_bil_invoice_types', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:100)->nullable();
            $table->timestamps();
        });

        // mst pay transaction status
        Schema::create('mst_pay_transaction_status', function(Blueprint $table) {
            $table->id();
            $table->string('name', length:60)->nullable();
            $table->timestamps();
        });

        // Payment gateway payment modules - mst_pay_modules
        Schema::create('mst_pay_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 32);
        });

        // MST Payment sources
        Schema::create('mst_pay_transaction_sources', function(Blueprint $table) {
            $table->id();
            $table->string('name', length: 32);
        });

        /**
         * Payment gateways
         */
        Schema::create('mst_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->nullable(); // Easebuzz, BBPS
            $table->enum('mode', ['test', 'production'])->nullable();
            $table->json('credentials')->nullable(); // encrypted
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['gateway', 'mode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('mst_sd_transaction_status');
        Schema::dropIfExists('mst_sd_payment_status');
        Schema::dropIfExists('mst_cmp_priorities');
        Schema::dropIfExists('mst_cmp_media');
        Schema::dropIfExists('mst_cmp_types');
        Schema::dropIfExists('mst_cmp_segments');
        Schema::dropIfExists('mst_cmp_status');
        Schema::dropIfExists('mst_cmp_categories');
        Schema::dropIfExists('mst_ref_status');
        Schema::dropIfExists('mst_pay_pg_data');
        Schema::dropIfExists('mst_pay_cheque_status');
        Schema::dropIfExists('mst_pay_status');
        Schema::dropIfExists('mst_pay_types');
        Schema::dropIfExists('mst_bil_status');
        Schema::dropIfExists('mst_bil_address');
        Schema::dropIfExists('mst_bil_invoice_items');
        Schema::dropIfExists('mst_bil_invoice_types');
        Schema::dropIfExists('mst_cns_geyser_status');
        Schema::dropIfExists('mst_cns_meter_status');
        Schema::dropIfExists('mst_cns_status');
        Schema::dropIfExists('mst_cns_gas_required');
        Schema::dropIfExists('mst_cns_nominee_relations');
        Schema::dropIfExists('mst_state_vat');
        Schema::dropIfExists('mst_price_history');
        Schema::dropIfExists('mst_price');
        Schema::dropIfExists('mst_taxes');
        Schema::dropIfExists('mst_tax_groups');
        Schema::dropIfExists('mst_bil_invoice_item_types');
        Schema::dropIfExists('mst_pay_transaction_status');
        Schema::dropIfExists('mst_cns_scheme_ga');
        Schema::dropIfExists('mst_cns_schemes');
        Schema::dropIfExists('mst_cns_scheme_payments');
        Schema::dropIfExists('mst_pay_modules');
        Schema::dropIfExists('mst_payment_gateways');
        Schema::dropIfExists('mst_titles');
        Schema::dropIfExists('mst_business_types');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
