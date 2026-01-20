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
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->string('name'); // e.g., "Quarterly Inspection"
            $table->text('description')->nullable();
            $table->enum('frequency', ['weekly', 'monthly', 'quarterly', 'semi_annual', 'annual'])->default('monthly');
            $table->integer('interval_days'); // calculated from frequency
            $table->date('start_date');
            $table->date('next_scheduled_date');
            $table->date('last_executed_date')->nullable();
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->text('maintenance_notes')->nullable();
            $table->integer('estimated_cost')->default(0); // in rupiah
            $table->integer('estimated_hours')->default(1);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
