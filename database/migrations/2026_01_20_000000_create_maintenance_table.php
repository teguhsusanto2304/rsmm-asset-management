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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Foreign Keys
            $table->foreignUuid('asset_id')->constrained('asset')->cascadeOnDelete();
            $table->foreignUuid('reported_by')->constrained('users')->cascadeOnDelete(); // User who owns the asset
            $table->foreignUuid('technician_id')->nullable()->constrained('users')->nullOnDelete(); // Technician assigned
            
            // Maintenance Information
            $table->enum('type', ['corrective', 'preventive', 'emergency'])->default('corrective'); // Type of maintenance
            $table->enum('status', ['reported', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('reported');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Description & Issues
            $table->text('issue_description'); // Description of the problem
            $table->text('symptoms')->nullable(); // Symptoms observed
            $table->text('work_performed')->nullable(); // Work done by technician
            $table->text('parts_replaced')->nullable(); // Parts that were replaced
            
            // Dates
            $table->dateTime('reported_date');
            $table->dateTime('assigned_date')->nullable();
            $table->dateTime('started_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            $table->integer('estimated_hours')->nullable(); // Estimated time to fix
            $table->integer('actual_hours')->nullable(); // Actual time spent
            
            // Costs
            $table->decimal('labor_cost', 10, 2)->nullable();
            $table->decimal('parts_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            
            // Additional Info
            $table->text('notes')->nullable(); // Additional notes
            $table->text('next_maintenance_notes')->nullable(); // Notes for future maintenance
            $table->string('reference_number')->unique()->nullable(); // Support ticket reference
            
            // Attachments
            $table->json('attachments')->nullable(); // File paths/URLs for images or documents
            
            // Audit Fields
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes
            $table->index('asset_id');
            $table->index('reported_by');
            $table->index('technician_id');
            $table->index('status');
            $table->index('priority');
            $table->index('type');
            $table->index('reported_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
