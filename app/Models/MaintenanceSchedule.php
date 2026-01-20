<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceSchedule extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'created_by',
        'name',
        'description',
        'frequency',
        'interval_days',
        'start_date',
        'next_scheduled_date',
        'last_executed_date',
        'status',
        'maintenance_notes',
        'estimated_cost',
        'estimated_hours',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_scheduled_date' => 'date',
        'last_executed_date' => 'date',
    ];

    // Relationships
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function generatedMaintenance()
    {
        return $this->hasMany(Maintenance::class, 'schedule_id');
    }

    // Helper methods
    public function getFrequencyLabel()
    {
        $labels = [
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Triwulanan',
            'semi_annual' => 'Enam Bulan',
            'annual' => 'Tahunan',
        ];
        return $labels[$this->frequency] ?? ucfirst($this->frequency);
    }

    public function getStatusLabel()
    {
        $labels = [
            'active' => 'Aktif',
            'paused' => 'Ditunda',
            'completed' => 'Selesai',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColor()
    {
        $colors = [
            'active' => 'bg-green-100 text-green-800',
            'paused' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-gray-100 text-gray-800',
        ];
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPriorityColor()
    {
        $colors = [
            'critical' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
        ];
        return $colors[$this->priority] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPriorityLabel()
    {
        $labels = [
            'critical' => 'Kritis',
            'high' => 'Tinggi',
            'medium' => 'Sedang',
            'low' => 'Rendah',
        ];
        return $labels[$this->priority] ?? ucfirst($this->priority);
    }

    public function isOverdue()
    {
        return $this->status === 'active' && $this->next_scheduled_date->isPast();
    }

    public function calculateNextDate()
    {
        return $this->start_date->addDays($this->interval_days);
    }

    public function canGenerateMaintenance()
    {
        return $this->status === 'active' && now()->toDateString() >= $this->next_scheduled_date->toDateString();
    }

    public function generateMaintenance()
    {
        if (!$this->canGenerateMaintenance()) {
            return null;
        }

        $maintenance = Maintenance::create([
            'asset_id' => $this->asset_id,
            'reported_by' => $this->created_by,
            'type' => 'preventive',
            'status' => 'assigned',
            'priority' => $this->priority,
            'issue_description' => "Pemeliharaan terjadwal: {$this->name}",
            'reported_date' => now(),
            'assigned_date' => now(),
            'reference_number' => 'PM-' . strtoupper(uniqid()),
        ]);

        // Update schedule
        $this->last_executed_date = now();
        $this->next_scheduled_date = $this->next_scheduled_date->addDays($this->interval_days);
        $this->save();

        return $maintenance;
    }
}
