<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Department;

class Location extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'parent_id',
        'type',
        'description',
        'status',
        'department_id'
    ];

    /* ================= Relations ================= */

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

