<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'maintenance';

    protected $fillable = [
        'asset_id',
        'reported_by',
        'technician_id',
        'schedule_id',
        'type',
        'status',
        'priority',
        'issue_description',
        'symptoms',
        'work_performed',
        'parts_replaced',
        'reported_date',
        'assigned_date',
        'started_date',
        'completed_date',
        'estimated_hours',
        'actual_hours',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'notes',
        'next_maintenance_notes',
        'reference_number',
        'attachments',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'reported_date' => 'datetime',
        'assigned_date' => 'datetime',
        'started_date' => 'datetime',
        'completed_date' => 'datetime',
        'attachments' => 'array',
    ];

    /* ================= Relations ================= */

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function reportedByUser()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function schedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id');
    }

    /* ================= Accessors & Mutators ================= */

    public function getStatusLabel()
    {
        return match($this->status) {
            'reported' => 'Dilaporkan',
            'assigned' => 'Ditugaskan',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'corrective' => 'Perbaikan',
            'preventive' => 'Pencegahan',
            'emergency' => 'Darurat',
            default => ucfirst($this->type),
        };
    }

    public function getPriorityLabel()
    {
        return match($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
            default => ucfirst($this->priority),
        };
    }

    public function getPriorityColor()
    {
        return match($this->priority) {
            'low' => 'bg-blue-100 text-blue-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'reported' => 'bg-gray-100 text-gray-800',
            'assigned' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /* ================= Scopes ================= */

    public function scopeReportedByUser($query, $userId)
    {
        return $query->where('reported_by', $userId);
    }

    public function scopeAssignedToTechnician($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['reported', 'assigned', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /* ================= Methods ================= */

    public function calculateTotalCost()
    {
        $this->total_cost = ($this->labor_cost ?? 0) + ($this->parts_cost ?? 0);
        return $this->total_cost;
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['reported', 'assigned']);
    }

    public function canBeStarted()
    {
        return $this->status === 'assigned' && $this->technician_id !== null;
    }

    public function canBeCompleted()
    {
        return $this->status === 'in_progress';
    }
}
