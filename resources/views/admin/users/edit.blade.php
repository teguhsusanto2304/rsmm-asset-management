@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Edit User</h2>
    <x-alert />
    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <x-input label="Nama Lengkap" name="name" value="{{ $user->name }}" required />
        <x-input label="Email" name="email" type="email" value="{{ $user->email }}" required />

        <x-input label="Password (optional)" name="password" type="password" />
        <x-input label="Konfirmasi Password" name="password_confirmation" type="password" />

        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="form-input w-full rounded-lg border-gray-300">
                @foreach(['Admin','Manager','User'] as $role)
                    <option value="{{ $role }}" @selected($user->role === $role)>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="form-input w-full rounded-lg border-gray-300">
                <option value="1" @selected($user->status)>Active</option>
                <option value="0" @selected(!$user->status)>Inactive</option>
            </select>
        </div>

        <x-form-actions label="Update" cancelLabel="Batal" cancelRoute="{{ route('departments.index') }}" />
    </form>
</div>
@endsection
