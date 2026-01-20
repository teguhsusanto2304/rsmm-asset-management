<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $mngusers = User::with('roles', 'department')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.users', compact('mngusers'));
    }

    public function create()
    {
        $departments = Department::orderBy('department')->get();
        return view('admin.users.create', compact('departments'));
    }

    public function edit(User $user)
    {
        // Load roles with permissions count
        $roles = Role::withCount('permissions')
            ->orderBy('name')
            ->get();
        
        // Get user's current role IDs
        $userRoles = $user->roles()->pluck('id')->toArray();
        
        // Get all departments for selection
        $departments = Department::orderBy('department')->get();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8|confirmed',
            'department_id' => 'nullable|uuid|exists:departments,id',
            'role'          => 'nullable|string',
            'status'        => 'required|in:active,inactive',
            'roles'         => 'array',
            'roles.*'       => 'exists:roles,id',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['status'] = $data['status'];

        $user = User::create($data);

        // Assign Spatie roles using Role models
        $roles = Role::whereIn('id', array_filter(array_map('intval', $data['roles'] ?? [])))->get();
        if ($roles->isNotEmpty()) {
            $user->syncRoles($roles);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => "required|email|unique:users,email,$user->id",
            'password'      => 'nullable|min:8|confirmed',
            'department_id' => 'nullable|uuid|exists:departments,id',
            'role'          => 'nullable|string',
            'status'        => 'required|in:active,inactive',
            'roles'         => 'array',
            'roles.*'       => 'exists:roles,id',
        ]);

        // Only update password if provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Sync Spatie roles using Role models
        $roles = Role::whereIn('id', array_filter(array_map('intval', $data['roles'] ?? [])))->get();
        $user->syncRoles($roles);

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted');
    }
}
