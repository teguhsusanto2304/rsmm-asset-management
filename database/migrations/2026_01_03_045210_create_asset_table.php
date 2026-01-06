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
        Schema::create('asset', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Basic Information
            $table->string('name');
            $table->string('barcode')->unique(); // Primary barcode identifier for scanning
            $table->string('asset_tag')->unique()->nullable(); // Optional internal asset tag
            $table->string('serial_number')->unique();
            $table->text('description')->nullable();
            
            // Classification
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignUuid('location_id')->nullable()->constrained('locations')->nullOnDelete();
            
            // Manufacturer & Model Information
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('model_number')->nullable();
            
            // Assignment & Ownership
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('assigned_date')->nullable();
            $table->date('return_date')->nullable();
            
            // Purchase Information
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->string('purchase_order_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_contact')->nullable();
            
            // Warranty Information
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->text('warranty_terms')->nullable();
            
            // Condition & Status
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'critical'])->default('good');
            $table->text('condition_notes')->nullable();
            $table->enum('status', ['available', 'assigned', 'maintenance', 'disposed', 'reserved'])->default('available');
            
            // Lifecycle Dates
            $table->date('installation_date')->nullable();
            $table->date('disposal_date')->nullable();
            $table->text('disposal_reason')->nullable();
            
            // Depreciation
            $table->decimal('depreciation_rate', 5, 2)->nullable(); // Annual depreciation percentage
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'units_of_production'])->nullable();
            $table->integer('useful_life_years')->nullable();
            
            // Insurance
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->decimal('insurance_value', 12, 2)->nullable();
            $table->date('insurance_expiry_date')->nullable();
            
            // Maintenance
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->integer('maintenance_interval_days')->nullable();
            $table->text('maintenance_notes')->nullable();
            
            // Additional Information
            $table->json('specifications')->nullable(); // For storing technical specs as JSON
            $table->string('image')->nullable(); // Path to asset image
            $table->json('images')->nullable(); // Multiple images
            $table->text('notes')->nullable(); // General notes
            
            // Audit Fields
            $table->softDeletes();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('barcode'); // Primary index for barcode scanning
            $table->index('asset_tag');
            $table->index('serial_number');
            $table->index('status');
            $table->index('condition');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset');
    }
};
