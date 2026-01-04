<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasUuids; // Automatically generates UUIDs on creation

    protected $fillable = [
        'parent_id',
        'department',
        'status',
        'user_id'
    ];

    /**
     * Get the parent department.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Get the sub-departments (children).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Get the head (user) of the department.
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}