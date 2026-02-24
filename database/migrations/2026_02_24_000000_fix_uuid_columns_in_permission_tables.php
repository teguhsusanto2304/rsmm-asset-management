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
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $modelKey = $columnNames['model_morph_key'] ?? 'model_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        // Disable foreign key checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Drop and recreate model_has_permissions table with UUID support
            Schema::dropIfExists($tableNames['model_has_permissions']);
            Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $modelKey) {
                $table->unsignedBigInteger($pivotPermission);
                $table->string('model_type');
                $table->string($modelKey);  // Changed to string for UUID support
                $table->index([$modelKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

                $table->foreign($pivotPermission)
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');
                    
                $table->primary([$pivotPermission, $modelKey, 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            });

            // Drop and recreate model_has_roles table with UUID support
            Schema::dropIfExists($tableNames['model_has_roles']);
            Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $modelKey) {
                $table->unsignedBigInteger($pivotRole);
                $table->string('model_type');
                $table->string($modelKey);  // Changed to string for UUID support
                $table->index([$modelKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

                $table->foreign($pivotRole)
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
                    
                $table->primary([$pivotRole, $modelKey, 'model_type'],
                    'model_has_roles_role_model_type_primary');
            });
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $modelKey = $columnNames['model_morph_key'] ?? 'model_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        // Disable foreign key checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Recreate model_has_permissions table with original unsignedBigInteger
            Schema::dropIfExists($tableNames['model_has_permissions']);
            Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $modelKey) {
                $table->unsignedBigInteger($pivotPermission);
                $table->string('model_type');
                $table->unsignedBigInteger($modelKey);  // Back to unsignedBigInteger
                $table->index([$modelKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

                $table->foreign($pivotPermission)
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');
                    
                $table->primary([$pivotPermission, $modelKey, 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            });

            // Recreate model_has_roles table with original unsignedBigInteger
            Schema::dropIfExists($tableNames['model_has_roles']);
            Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $modelKey) {
                $table->unsignedBigInteger($pivotRole);
                $table->string('model_type');
                $table->unsignedBigInteger($modelKey);  // Back to unsignedBigInteger
                $table->index([$modelKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

                $table->foreign($pivotRole)
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
                    
                $table->primary([$pivotRole, $modelKey, 'model_type'],
                    'model_has_roles_role_model_type_primary');
            });
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
};
