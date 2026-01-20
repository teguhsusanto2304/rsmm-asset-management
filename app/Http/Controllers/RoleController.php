<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query()
            ->withCount('permissions', 'users')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            })
            ->where('name', '!=', 'super_admin') // Hide super_admin from regular users
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama role harus diisi',
            'name.unique' => 'Nama role sudah ada',
            'description.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dibuat');
    }

    public function edit(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')
                ->withErrors(['error' => 'Anda tidak dapat mengedit super_admin role']);
        }

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')
                ->withErrors(['error' => 'Anda tidak dapat mengedit super_admin role']);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id . '|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama role harus diisi',
            'name.unique' => 'Nama role sudah ada',
            'description.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    public function showPermissions(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')
                ->withErrors(['error' => 'Anda tidak dapat mengedit permissions super_admin role']);
        }

        $rolePermissions = $role->permissions()->pluck('id')->toArray();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')
                ->withErrors(['error' => 'Anda tidak dapat mengedit permissions super_admin role']);
        }

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Get Permission models instead of passing IDs
        $permissionIds = array_filter(array_map('intval', $validated['permissions'] ?? []));
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Permissions role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus super_admin role']);
        }

        // Check if role is assigned to any user
        if ($role->users()->exists()) {
            return back()->withErrors(['error' => 'Role ini masih digunakan oleh beberapa user']);
        }

        $role->delete();

        return back()->with('success', 'Role berhasil dihapus');
    }
}
