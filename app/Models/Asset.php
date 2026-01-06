<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'asset';

    protected $fillable = [
        'name',
        'barcode',
        'asset_tag',
        'serial_number',
        'description',
        'category_id',
        'department_id',
        'location_id',
        'manufacturer',
        'model',
        'model_number',
        'assigned_to',
        'assigned_date',
        'return_date',
        'purchase_date',
        'purchase_price',
        'current_value',
        'purchase_order_number',
        'invoice_number',
        'supplier',
        'supplier_contact',
        'warranty_start_date',
        'warranty_end_date',
        'warranty_terms',
        'condition',
        'condition_notes',
        'status',
        'installation_date',
        'disposal_date',
        'disposal_reason',
        'depreciation_rate',
        'depreciation_method',
        'useful_life_years',
        'insurance_provider',
        'insurance_policy_number',
        'insurance_value',
        'insurance_expiry_date',
        'last_maintenance_date',
        'next_maintenance_date',
        'maintenance_interval_days',
        'maintenance_notes',
        'specifications',
        'image',
        'images',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'assigned_date' => 'date',
        'return_date' => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
        'installation_date' => 'date',
        'disposal_date' => 'date',
        'insurance_expiry_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'specifications' => 'array',
        'images' => 'array',
    ];

    /* ================= Relations ================= */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}


