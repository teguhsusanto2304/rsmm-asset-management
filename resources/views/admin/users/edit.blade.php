@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-3xl">edit</span>
        Edit User
    </h2>
    <x-alert />
    
    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information Section -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-800">
                <span class="material-symbols-outlined">person</span>
                Informasi Dasar
            </h3>
            <div class="space-y-4">
                <x-input label="Nama Lengkap" name="name" value="{{ $user->name }}" required />
                <x-input label="Email" name="email" type="email" value="{{ $user->email }}" required />
                <x-input label="Password (biarkan kosong jika tidak ingin mengubah)" name="password" type="password" />
                <x-input label="Konfirmasi Password" name="password_confirmation" type="password" />
            </div>
        </div>

        <!-- Organization Section -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-800">
                <span class="material-symbols-outlined">apartment</span>
                Organisasi
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                    <select name="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected($user->department_id === $dept->id)>
                                {{ $dept->department }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="active" @selected($user->status === 'active')>Active</option>
                        <option value="inactive" @selected($user->status === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Roles Section -->
        <div class="border-t pt-6">
            <x-role-selector 
                :roles="$roles" 
                :selectedRoles="$userRoles" 
                name="roles"
            />
        </div>

        <!-- Form Actions -->
        <div class="border-t pt-6 flex gap-3 justify-end">
            <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Update User
            </button>
        </div>
    </form>
</div>
@endsection
