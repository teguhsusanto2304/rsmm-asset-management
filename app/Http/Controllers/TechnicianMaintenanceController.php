<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class TechnicianMaintenanceController extends Controller
{
    /**
     * Display technician dashboard with assigned maintenance.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Check if user is a technician
        if (!$user->hasRole('technician')) {
            abort(403, 'Only technicians can access this area');
        }

        $assigned = Maintenance::where('technician_id', $user->id)
            ->where('status', 'assigned')
            ->with('asset', 'reportedByUser')
            ->orderBy('priority', 'desc')
            ->orderBy('reported_date', 'asc')
            ->get();

        $inProgress = Maintenance::where('technician_id', $user->id)
            ->where('status', 'in_progress')
            ->with('asset', 'reportedByUser')
            ->orderBy('started_date', 'asc')
            ->get();

        $completed = Maintenance::where('technician_id', $user->id)
            ->where('status', 'completed')
            ->with('asset', 'reportedByUser')
            ->orderBy('completed_date', 'desc')
            ->limit(10)
            ->get();

        // Calculate statistics
        $stats = [
            'total' => Maintenance::where('technician_id', $user->id)
                ->whereIn('status', ['assigned', 'in_progress', 'completed'])
                ->count(),
            'pending' => $assigned->count(),
            'in_progress' => $inProgress->count(),
            'completed_month' => Maintenance::where('technician_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('completed_date', now()->month)
                ->whereYear('completed_date', now()->year)
                ->count(),
        ];

        return view('admin.technician.dashboard', compact(
            'assigned',
            'inProgress',
            'completed',
            'stats'
        ));
    }

    /**
     * Display all maintenance for technician with filters.
     */
    public function maintenance(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasRole('technician')) {
            abort(403, 'Only technicians can access this area');
        }

        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Maintenance::where('technician_id', $user->id);

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search by reference number or asset name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('asset', function ($assetQuery) use ($search) {
                        $assetQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%");
                    });
            });
        }

        $maintenance = $query->with('asset', 'reportedByUser')
            ->orderBy('status', 'asc')
            ->orderBy('priority', 'desc')
            ->orderBy('reported_date', 'asc')
            ->paginate(15);

        return view('admin.technician.maintenance', compact('maintenance', 'status', 'search'));
    }

    /**
     * Display detailed report card for a maintenance job.
     */
    public function report(Maintenance $maintenance)
    {
        $user = auth()->user();

        if (!$user->hasRole('technician') || $maintenance->technician_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $maintenance->load('asset', 'reportedByUser');

        return view('admin.technician.report', compact('maintenance'));
    }

    /**
     * Get technician statistics.
     */
    public function statistics()
    {
        $user = auth()->user();

        if (!$user->hasRole('technician')) {
            abort(403, 'Only technicians can access this area');
        }

        // Get all maintenance for this technician
        $allMaintenance = Maintenance::where('technician_id', $user->id)->get();

        // Calculate basic statistics
        $totalJobs = $allMaintenance->count();
        $assignedJobs = $allMaintenance->where('status', 'assigned')->count();
        $inProgressJobs = $allMaintenance->where('status', 'in_progress')->count();
        
        // Completed jobs this month
        $completedJobs = Maintenance::where('technician_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('completed_date', now()->month)
            ->whereYear('completed_date', now()->year)
            ->count();

        // Calculate average completion time
        $completedMaintenance = Maintenance::where('technician_id', $user->id)
            ->where('status', 'completed')
            ->get();
        $avgCompletionTime = 0;
        if ($completedMaintenance->count() > 0) {
            $totalHours = $completedMaintenance->sum(function($m) {
                return $m->actual_hours ?? 0;
            });
            $avgCompletionTime = $totalHours / $completedMaintenance->count();
        }

        // Calculate monthly stats for last 6 months
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Maintenance::where('technician_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('completed_date', $date->month)
                ->whereYear('completed_date', $date->year)
                ->count();
            $monthlyStats[] = [
                'month' => $date->format('M'),
                'completed' => $count,
            ];
        }

        // Calculate total costs
        $totalLaborCost = Maintenance::where('technician_id', $user->id)
            ->where('status', 'completed')
            ->sum('labor_cost');
        $totalPartsCost = Maintenance::where('technician_id', $user->id)
            ->where('status', 'completed')
            ->sum('parts_cost');
        $totalHoursThisMonth = Maintenance::where('technician_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('completed_date', now()->month)
            ->whereYear('completed_date', now()->year)
            ->sum('actual_hours');

        // Calculate completion rate
        $completionRate = $totalJobs > 0 ? ($completedJobs / $totalJobs) * 100 : 0;

        // Priority distribution
        $priorityDistribution = [];
        foreach (['low', 'medium', 'high', 'critical'] as $priority) {
            $priorityDistribution[$priority] = $allMaintenance->where('priority', $priority)->count();
        }

        // Type distribution
        $typeDistribution = [];
        foreach (['corrective', 'preventive', 'emergency'] as $type) {
            $typeDistribution[$type] = $allMaintenance->where('type', $type)->count();
        }

        return view('admin.technician.statistics', compact(
            'totalJobs',
            'assignedJobs',
            'inProgressJobs',
            'completedJobs',
            'avgCompletionTime',
            'totalHoursThisMonth',
            'totalLaborCost',
            'totalPartsCost',
            'completionRate',
            'monthlyStats',
            'priorityDistribution',
            'typeDistribution'
        ));
    }
}
