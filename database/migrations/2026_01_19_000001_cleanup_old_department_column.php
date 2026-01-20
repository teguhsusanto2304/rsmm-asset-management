<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration cleans up the old department string column
        // The data has already been migrated to department_id via the new relationship
        // This is a safe migration that keeps both columns for now, but notes that
        // the department string column is deprecated and should not be used
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
