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
        Schema::create('departments', function (Blueprint $table) {
            // 1. ID as UUID Primary Key
            $table->uuid('id')->primary();
            
            // 2. ID Parent (Nullable for top-level departments)
            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('departments')
                ->onDelete('cascade');
            $table->foreignId('user_id')
      ->constrained('users')
      ->onDelete('cascade');
            // 3. Department Name
            $table->string('department');

            // 4. Status (e.g., active, inactive)
            $table->string('status')->default('active');
            $table->timestamps();
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
