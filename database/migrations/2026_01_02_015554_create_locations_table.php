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
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->string('name');
            $table->uuid('parent_id')->nullable();

            $table->enum('type', ['gedung', 'lantai', 'ruang']);
            $table->text('description')->nullable();

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // 🔗 Relation to department
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();

            $table->timestamps();

            // self reference
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('locations')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
