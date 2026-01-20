<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenance records.
     */
    public function index()
    {
        $user = auth()->user();
        
        // If technician, show assigned maintenance
        if ($user->hasRole('technician')) {
            $maintenance = Maintenance::with('asset', 'reportedByUser', 'technician')
                ->where('technician_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Regular users see their own reports
            $maintenance = Maintenance::with('asset', 'reportedByUser', 'technician')
                ->where('reported_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.maintenance.index', compact('maintenance'));
    }

    /**
     * Show form to create corrective maintenance for own asset.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Get user's assigned assets that are not in maintenance status
        $myAssets = Asset::where('assigned_to', $user->id)
            ->where('status', '!=', 'disposed')
            ->get();

        return view('admin.maintenance.create', compact('myAssets'));
    }

    /**
     * Store a newly created maintenance record.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|uuid|exists:asset,id',
            'type' => 'required|in:corrective,preventive,emergency',
            'priority' => 'required|in:low,medium,high,critical',
            'issue_description' => 'required|string|min:10|max:1000',
            'symptoms' => 'nullable|string|max:500',
            'estimated_hours' => 'nullable|integer|min:1|max:100',
        ], [
            'asset_id.required' => 'Asset harus dipilih',
            'asset_id.exists' => 'Asset tidak ditemukan',
            'type.required' => 'Jenis pemeliharaan harus dipilih',
            'priority.required' => 'Prioritas harus dipilih',
            'issue_description.required' => 'Deskripsi masalah harus diisi',
            'issue_description.min' => 'Deskripsi minimal 10 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify asset belongs to user
        $asset = Asset::find($request->asset_id);
        if ($asset->assigned_to !== $user->id) {
            return back()->withErrors(['asset_id' => 'Asset tidak milik Anda'])->withInput();
        }

        // Generate reference number
        $referenceNumber = 'MNT-' . now()->format('Y-m-d') . '-' . Str::random(6);

        // Create maintenance record
        $maintenance = Maintenance::create([
            'asset_id' => $request->asset_id,
            'reported_by' => $user->id,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => 'reported',
            'issue_description' => $request->issue_description,
            'symptoms' => $request->symptoms,
            'reported_date' => now(),
            'estimated_hours' => $request->estimated_hours,
            'reference_number' => $referenceNumber,
            'created_by' => $user->id,
        ]);

        // Update asset status if corrective or emergency
        if (in_array($request->type, ['corrective', 'emergency'])) {
            $asset->update(['status' => 'maintenance']);
        }

        return redirect()
            ->route('maintenance.show', $maintenance)
            ->with('success', 'Laporan pemeliharaan berhasil dibuat. Nomor referensi: ' . $referenceNumber);
    }

    /**
     * Display the specified maintenance record.
     */
    public function show(Maintenance $maintenance)
    {
        $user = auth()->user();
        
        // Check authorization
        if ($maintenance->reported_by !== $user->id && $maintenance->technician_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $maintenance->load('asset', 'reportedByUser', 'technician');

        return view('admin.maintenance.show', compact('maintenance'));
    }

    /**
     * Show form to edit maintenance record.
     */
    public function edit(Maintenance $maintenance)
    {
        $user = auth()->user();
        
        // Only allow editing if status is reported or assigned
        if (!$maintenance->canBeEdited()) {
            return back()->withErrors(['error' => 'Pemeliharaan tidak dapat diedit lagi']);
        }

        // Check authorization
        if ($maintenance->reported_by !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        return view('admin.maintenance.edit', compact('maintenance'));
    }

    /**
     * Update the specified maintenance record.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $user = auth()->user();

        // Check authorization and editing permission
        if ($maintenance->reported_by !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        if (!$maintenance->canBeEdited()) {
            return back()->withErrors(['error' => 'Pemeliharaan tidak dapat diedit lagi']);
        }

        $validator = Validator::make($request->all(), [
            'priority' => 'required|in:low,medium,high,critical',
            'issue_description' => 'required|string|min:10|max:1000',
            'symptoms' => 'nullable|string|max:500',
            'estimated_hours' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $maintenance->update([
            'priority' => $request->priority,
            'issue_description' => $request->issue_description,
            'symptoms' => $request->symptoms,
            'estimated_hours' => $request->estimated_hours,
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->route('maintenance.show', $maintenance)
            ->with('success', 'Laporan pemeliharaan berhasil diperbarui');
    }

    /**
     * Assign maintenance to technician (Admin/Manager only).
     */
    public function assign(Request $request, Maintenance $maintenance)
    {
        $user = auth()->user();

        if (!$user->hasRole(['admin', 'manager'])) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'technician_id' => 'required|uuid|exists:users,id',
        ], [
            'technician_id.required' => 'Teknisi harus dipilih',
            'technician_id.exists' => 'Teknisi tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $technician = User::find($request->technician_id);
        if (!$technician->hasRole('technician')) {
            return back()->withErrors(['technician_id' => 'User bukan teknisi']);
        }

        $maintenance->update([
            'technician_id' => $request->technician_id,
            'status' => 'assigned',
            'assigned_date' => now(),
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Pemeliharaan berhasil ditugaskan ke ' . $technician->name);
    }

    /**
     * Start working on maintenance (Technician only).
     */
    public function start(Maintenance $maintenance)
    {
        $user = auth()->user();

        if (!$user->hasRole('technician') || $maintenance->technician_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (!$maintenance->canBeStarted()) {
            return back()->withErrors(['error' => 'Pemeliharaan tidak dapat dimulai']);
        }

        $maintenance->update([
            'status' => 'in_progress',
            'started_date' => now(),
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Pemeliharaan sedang dikerjakan');
    }

    /**
     * Complete maintenance with report (Technician only).
     */
    public function complete(Request $request, Maintenance $maintenance)
    {
        $user = auth()->user();

        if (!$user->hasRole('technician') || $maintenance->technician_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (!$maintenance->canBeCompleted()) {
            return back()->withErrors(['error' => 'Pemeliharaan tidak dapat diselesaikan']);
        }

        $validator = Validator::make($request->all(), [
            'work_performed' => 'required|string|min:10|max:1000',
            'parts_replaced' => 'nullable|string|max:500',
            'actual_hours' => 'required|integer|min:1|max:100',
            'labor_cost' => 'required|numeric|min:0|max:999999.99',
            'parts_cost' => 'nullable|numeric|min:0|max:999999.99',
            'next_maintenance_notes' => 'nullable|string|max:500',
        ], [
            'work_performed.required' => 'Pekerjaan yang dilakukan harus diisi',
            'actual_hours.required' => 'Jam kerja aktual harus diisi',
            'labor_cost.required' => 'Biaya tenaga kerja harus diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $laborCost = $request->labor_cost ?? 0;
        $partsCost = $request->parts_cost ?? 0;
        $totalCost = $laborCost + $partsCost;

        $maintenance->update([
            'status' => 'completed',
            'completed_date' => now(),
            'work_performed' => $request->work_performed,
            'parts_replaced' => $request->parts_replaced,
            'actual_hours' => $request->actual_hours,
            'labor_cost' => $laborCost,
            'parts_cost' => $partsCost,
            'total_cost' => $totalCost,
            'next_maintenance_notes' => $request->next_maintenance_notes,
            'updated_by' => $user->id,
        ]);

        // Update asset status back to available
        $maintenance->asset->update([
            'status' => 'available',
            'last_maintenance_date' => now(),
        ]);

        return redirect()
            ->route('maintenance.show', $maintenance)
            ->with('success', 'Pemeliharaan berhasil diselesaikan dan laporan telah dikirim ke pengguna');
    }

    /**
     * Cancel maintenance.
     */
    public function cancel(Request $request, Maintenance $maintenance)
    {
        $user = auth()->user();

        if (!$user->hasRole(['admin', 'manager']) && $maintenance->reported_by !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|min:5|max:500',
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan harus diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $maintenance->update([
            'status' => 'cancelled',
            'notes' => $request->cancellation_reason,
            'updated_by' => $user->id,
        ]);

        // Reset asset status
        if ($maintenance->asset->status === 'maintenance') {
            $maintenance->asset->update(['status' => 'available']);
        }

        return back()->with('success', 'Pemeliharaan berhasil dibatalkan');
    }

    /**
     * Get technician list for assignment (Ajax).
     */
    public function getTechnicians()
    {
        $technicians = User::whereHas('roles', function ($query) {
            $query->where('name', 'technician');
        })->select('id', 'name', 'email')->get();

        return response()->json($technicians);
    }
}
