@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Tambah User</h2>
    <x-alert />
    <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
        @csrf

        <x-input label="Nama Lengkap" name="name" required />
        <x-input label="Email" name="email" type="email" required />

        <x-input label="Password" name="password" type="password" required />
        <x-input label="Konfirmasi Password" name="password_confirmation" type="password" required />

        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="form-input w-full rounded-lg border-gray-300">
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
                <option value="User">User</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="form-input w-full rounded-lg border-gray-300">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <x-form-actions label="Simpan" cancelLabel="Batal" cancelRoute="{{ route('users.index') }}" />
    </form>
</div>
@endsection
