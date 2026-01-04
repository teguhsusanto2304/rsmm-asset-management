<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $mngusers = User::when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->when($request->role, fn ($q) => $q->where('role', $request->role))
            ->paginate(10);

        return view('admin.users.users', compact('mngusers'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit',compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'department' => 'nullable',
            'role'       => 'required',
            'status'     => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = strtolower($data['role']);
        $data['status'] = strtolower($data['status']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required',
            'email'      => "required|email|unique:users,email,$user->id",
            'department' => 'nullable',
            'role'       => 'required',
            'status'     => 'required',
        ]);

        $user->update($data);

        return back()->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted');
    }
}
