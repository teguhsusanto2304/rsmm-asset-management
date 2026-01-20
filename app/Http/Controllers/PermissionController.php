<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama permission harus diisi',
            'name.unique' => 'Nama permission sudah ada',
            'description.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dibuat');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id . '|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama permission harus diisi',
            'name.unique' => 'Nama permission sudah ada',
            'description.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil diperbarui');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any role
        if ($permission->roles()->exists()) {
            return back()->withErrors(['error' => 'Permission ini masih digunakan oleh beberapa role']);
        }

        $permission->delete();

        return back()->with('success', 'Permission berhasil dihapus');
    }
}
