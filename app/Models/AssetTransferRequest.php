<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTransferRequest extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'requested_by',
        'requested_from',
        'requested_to',
        'type',
        'status',
        'request_date',
        'expected_return_date',
        'actual_return_date',
        'reason',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'request_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    /* ================= Relations ================= */

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function requestedByUser()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function requestedFromUser()
    {
        return $this->belongsTo(User::class, 'requested_from');
    }

    public function requestedToUser()
    {
        return $this->belongsTo(User::class, 'requested_to');
    }

    /* ================= Scopes ================= */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /* ================= Methods ================= */

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    public function markReturned()
    {
        $this->update([
            'status' => 'returned',
            'actual_return_date' => now()->format('Y-m-d'),
        ]);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-purple-100 text-purple-800',
            'returned' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'completed' => 'Sedang Dipinjam',
            'returned' => 'Dikembalikan',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'borrow' => 'Pinjam',
            'move' => 'Pindah',
            default => ucfirst($this->type),
        };
    }
}
