<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetTransferRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AssetTransferRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetTransferRequest::with(['asset', 'requestedByUser', 'requestedFromUser', 'requestedToUser']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Handle quick filters
        if ($request->quick_filter) {
            if ($request->quick_filter === 'my_requests') {
                $query->where('requested_by', auth()->id());
            } elseif ($request->quick_filter === 'for_me') {
                $query->where('requested_to', auth()->id());
            } elseif ($request->quick_filter === 'from_my_assets') {
                $query->where('requested_from', auth()->id());
            }
        }

        // Filter for current user's requests
        if ($request->my_requests) {
            $query->where('requested_by', auth()->id());
        }

        // Filter for requests sent to current user
        if ($request->for_me) {
            $query->where('requested_to', auth()->id());
        }

        // Filter for requests from asset I own
        if ($request->from_my_assets) {
            $query->where('requested_from', auth()->id());
        }

        $requests = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $statuses = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'completed' => 'Sedang Dipinjam', 'returned' => 'Dikembalikan', 'rejected' => 'Ditolak'];
        $types = ['borrow' => 'Pinjam', 'move' => 'Pindah'];

        return view('admin.asset-transfers.index', compact('requests', 'statuses', 'types'));
    }

    public function create(Request $request)
    {
        $assetId = $request->asset_id;
        $asset = null;

        if ($assetId) {
            $asset = Asset::with(['assignedUser', 'category', 'department'])->findOrFail($assetId);
        }

        $users = User::orderBy('name')->get();
        $myAssets = Asset::where('assigned_to', auth()->id())
            ->orWhere('requested_from', auth()->id())
            ->with('category')
            ->get();

        return view('admin.asset-transfers.create', compact('asset', 'users', 'myAssets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:asset,id',
            'requested_to' => 'required|exists:users,id|different:requested_from',
            'requested_from' => 'required|exists:users,id',
            'type' => 'required|in:borrow,move',
            'expected_return_date' => 'required_if:type,borrow|nullable|date|after:today',
            'reason' => 'required|string|min:5',
        ], [
            'asset_id.required' => 'Pilih asset yang ingin diminta',
            'requested_to.required' => 'Pilih penerima asset',
            'requested_to.different' => 'Penerima tidak boleh sama dengan pemberi',
            'requested_from.required' => 'Pilih pemberi asset',
            'type.required' => 'Pilih jenis permintaan',
            'expected_return_date.required_if' => 'Tanggal pengembalian harus diisi untuk peminjaman',
            'reason.required' => 'Alasan permintaan harus diisi',
            'reason.min' => 'Alasan minimal 5 karakter',
        ]);

        $asset = Asset::findOrFail($validated['asset_id']);

        // Check if asset is already owned by requested_to
        if ($asset->assigned_to == $validated['requested_to']) {
            return back()->withErrors(['asset_id' => 'Asset sudah dimiliki oleh penerima']);
        }

        AssetTransferRequest::create([
            'asset_id' => $validated['asset_id'],
            'requested_by' => auth()->id(),
            'requested_from' => $validated['requested_from'],
            'requested_to' => $validated['requested_to'],
            'type' => $validated['type'],
            'status' => 'pending',
            'request_date' => now(),
            'expected_return_date' => $validated['expected_return_date'] ?? null,
            'reason' => $validated['reason'],
            'notes' => $request->notes,
        ]);

        return redirect()->route('asset-transfers.index')
            ->with('success', 'Permintaan transfer asset berhasil dibuat');
    }

    public function show(AssetTransferRequest $assetTransfer)
    {
        $assetTransfer->load(['asset', 'requestedByUser', 'requestedFromUser', 'requestedToUser']);
        return view('admin.asset-transfers.show', compact('assetTransfer'));
    }

    public function approve(AssetTransferRequest $assetTransfer)
    {
        // Only the asset owner (requested_from) can approve
        if ($assetTransfer->requested_from != auth()->id()) {
            return back()->withErrors(['access' => 'Anda tidak memiliki akses untuk menyetujui permintaan ini']);
        }

        if ($assetTransfer->status != 'pending') {
            return back()->withErrors(['status' => 'Permintaan ini tidak dapat disetujui']);
        }

        $assetTransfer->approve();

        return back()->with('success', 'Permintaan transfer asset telah disetujui');
    }

    public function reject(Request $request, AssetTransferRequest $assetTransfer)
    {
        // Only the asset owner (requested_from) can reject
        if ($assetTransfer->requested_from != auth()->id()) {
            return back()->withErrors(['access' => 'Anda tidak memiliki akses untuk menolak permintaan ini']);
        }

        if ($assetTransfer->status != 'pending') {
            return back()->withErrors(['status' => 'Permintaan ini tidak dapat ditolak']);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
            'rejection_reason.min' => 'Alasan minimal 5 karakter',
        ]);

        $assetTransfer->reject($validated['rejection_reason']);

        return back()->with('success', 'Permintaan transfer asset telah ditolak');
    }

    public function complete(AssetTransferRequest $assetTransfer)
    {
        // Only the asset owner (requested_from) or requester can mark as completed
        if ($assetTransfer->requested_from != auth()->id() && $assetTransfer->requested_by != auth()->id()) {
            return back()->withErrors(['access' => 'Anda tidak memiliki akses']);
        }

        if ($assetTransfer->status != 'approved') {
            return back()->withErrors(['status' => 'Hanya permintaan yang disetujui yang dapat dipindahkan']);
        }

        // Update the asset ownership
        $assetTransfer->asset->update([
            'assigned_to' => $assetTransfer->requested_to,
            'assigned_date' => now(),
        ]);

        $assetTransfer->complete();

        return back()->with('success', 'Asset berhasil dipindahkan ke pemilik baru');
    }

    public function markReturned(AssetTransferRequest $assetTransfer)
    {
        // Only the new asset owner (requested_to) can mark as returned
        if ($assetTransfer->requested_to != auth()->id()) {
            return back()->withErrors(['access' => 'Anda tidak memiliki akses']);
        }

        if ($assetTransfer->status != 'completed' || $assetTransfer->type != 'borrow') {
            return back()->withErrors(['status' => 'Asset ini tidak dapat dikembalikan']);
        }

        // Return asset to original owner
        $assetTransfer->asset->update([
            'assigned_to' => $assetTransfer->requested_from,
        ]);

        $assetTransfer->markReturned();

        return back()->with('success', 'Asset berhasil dikembalikan ke pemilik asli');
    }

    public function cancel(AssetTransferRequest $assetTransfer)
    {
        // Only the requester can cancel pending requests
        if ($assetTransfer->requested_by != auth()->id()) {
            return back()->withErrors(['access' => 'Anda tidak memiliki akses untuk membatalkan permintaan ini']);
        }

        if ($assetTransfer->status != 'pending') {
            return back()->withErrors(['status' => 'Hanya permintaan yang masih menunggu yang dapat dibatalkan']);
        }

        $assetTransfer->delete();

        return back()->with('success', 'Permintaan transfer asset berhasil dibatalkan');
    }
}
