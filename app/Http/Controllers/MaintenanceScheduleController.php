<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display maintenance schedules list.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Build query
        $query = MaintenanceSchedule::with('asset', 'creator');

        // If user is not admin/manager, only show schedules for their own assets
        if (!$user->hasAnyRole(['admin', 'manager'])) {
            $query->whereHas('asset', function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('asset', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $schedules = $query->orderBy('next_scheduled_date', 'asc')
                           ->orderBy('priority', 'desc')
                           ->paginate(15);

        return view('admin.maintenance-schedule.index', [
            'schedules' => $schedules,
            'status' => $request->status ?? 'all',
            'search' => $request->search ?? '',
        ]);
    }

    /**
     * Show create schedule form.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Get assets - admin/manager can see all, others see their own
        if ($user->hasAnyRole(['admin', 'manager'])) {
            $assets = Asset::with('department', 'location', 'category')
                           ->orderBy('name')
                           ->get();
        } else {
            $assets = Asset::where('assigned_to', $user->id)
                           ->with('department', 'location', 'category')
                           ->orderBy('name')
                           ->get();
        }

        $frequencies = [
            'weekly' => 'Mingguan (7 hari)',
            'monthly' => 'Bulanan (30 hari)',
            'quarterly' => 'Triwulanan (90 hari)',
            'semi_annual' => 'Enam Bulan (180 hari)',
            'annual' => 'Tahunan (365 hari)',
        ];

        $priorities = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
        ];

        return view('admin.maintenance-schedule.create', compact('assets', 'frequencies', 'priorities'));
    }

    /**
     * Store maintenance schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|uuid|exists:assets,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:weekly,monthly,quarterly,semi_annual,annual',
            'start_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:active,paused',
            'maintenance_notes' => 'nullable|string',
            'estimated_cost' => 'required|integer|min:0',
            'estimated_hours' => 'required|integer|min:1',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        // Calculate interval days
        $intervalDays = [
            'weekly' => 7,
            'monthly' => 30,
            'quarterly' => 90,
            'semi_annual' => 180,
            'annual' => 365,
        ];

        $validated['created_by'] = auth()->id();
        $validated['interval_days'] = $intervalDays[$validated['frequency']];
        $validated['next_scheduled_date'] = now()->parse($validated['start_date'])->addDays($intervalDays[$validated['frequency']]);

        MaintenanceSchedule::create($validated);

        return redirect()->route('maintenance-schedule.index')
                       ->with('success', 'Jadwal pemeliharaan berhasil dibuat');
    }

    /**
     * Show schedule details.
     */
    public function show(MaintenanceSchedule $maintenanceSchedule)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->hasAnyRole(['admin', 'manager']) && 
            $maintenanceSchedule->asset->assigned_to !== $user->id) {
            abort(403);
        }

        $maintenanceSchedule->load('asset', 'creator', 'generatedMaintenance');

        return view('admin.maintenance-schedule.show', [
            'schedule' => $maintenanceSchedule,
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit(MaintenanceSchedule $maintenanceSchedule)
    {
        $user = auth()->user();

        // Authorization
        if (!$user->hasAnyRole(['admin', 'manager']) && 
            $maintenanceSchedule->asset->assigned_to !== $user->id) {
            abort(403);
        }

        $assets = Asset::with('department', 'location', 'category')
                       ->orderBy('name')
                       ->get();

        $frequencies = [
            'weekly' => 'Mingguan (7 hari)',
            'monthly' => 'Bulanan (30 hari)',
            'quarterly' => 'Triwulanan (90 hari)',
            'semi_annual' => 'Enam Bulan (180 hari)',
            'annual' => 'Tahunan (365 hari)',
        ];

        $priorities = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
        ];

        return view('admin.maintenance-schedule.edit', compact('maintenanceSchedule', 'assets', 'frequencies', 'priorities'));
    }

    /**
     * Update maintenance schedule.
     */
    public function update(Request $request, MaintenanceSchedule $maintenanceSchedule)
    {
        $user = auth()->user();

        // Authorization
        if (!$user->hasAnyRole(['admin', 'manager']) && 
            $maintenanceSchedule->asset->assigned_to !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'asset_id' => 'required|uuid|exists:assets,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:weekly,monthly,quarterly,semi_annual,annual',
            'start_date' => 'required|date',
            'status' => 'required|in:active,paused,completed',
            'maintenance_notes' => 'nullable|string',
            'estimated_cost' => 'required|integer|min:0',
            'estimated_hours' => 'required|integer|min:1',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        // If frequency changed, recalculate
        if ($validated['frequency'] !== $maintenanceSchedule->frequency) {
            $intervalDays = [
                'weekly' => 7,
                'monthly' => 30,
                'quarterly' => 90,
                'semi_annual' => 180,
                'annual' => 365,
            ];
            $validated['interval_days'] = $intervalDays[$validated['frequency']];
        }

        $maintenanceSchedule->update($validated);

        return redirect()->route('maintenance-schedule.show', $maintenanceSchedule)
                       ->with('success', 'Jadwal pemeliharaan berhasil diperbarui');
    }

    /**
     * Delete schedule.
     */
    public function destroy(MaintenanceSchedule $maintenanceSchedule)
    {
        $user = auth()->user();

        // Authorization
        if (!$user->hasAnyRole(['admin', 'manager']) && 
            $maintenanceSchedule->asset->assigned_to !== $user->id) {
            abort(403);
        }

        $maintenanceSchedule->delete();

        return redirect()->route('maintenance-schedule.index')
                       ->with('success', 'Jadwal pemeliharaan berhasil dihapus');
    }

    /**
     * Pause schedule.
     */
    public function pause(MaintenanceSchedule $maintenanceSchedule)
    {
        $user = auth()->user();

        if (!$user->hasAnyRole(['admin', 'manager']) && 
            $maintenanceSchedule->asset->assigned_to !== $user->id) {
            abort(403);
        }

        $maintenanceSchedule->update(['status' => 'paused']);

        return back()->with('success', 'Jadwal pemeliharaan berhasil ditunda');
    }

    /**
     * Resume schedule.
     */
    public function resume(MaintenanceSchedule $maintenanceSchedule)
    {
        $user = auth()->user();

        if (!$user->hasAnyRole(['admin', 'manager']) && 
            $maintenanceSchedule->asset->assigned_to !== $user->id) {
            abort(403);
        }

        $maintenanceSchedule->update(['status' => 'active']);

        return back()->with('success', 'Jadwal pemeliharaan berhasil dilanjutkan');
    }
}
