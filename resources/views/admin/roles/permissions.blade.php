@extends('layouts.admin.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Kelola Permission untuk Role</h1>
            <p class="text-[#617989]">Role: <strong>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</strong></p>
        </div>
        <a href="{{ route('roles.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('roles.update-permissions', $role) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Permissions Grid --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
            <div class="mb-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Pilih Permission</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAllPermissions()" class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded hover:bg-blue-200">
                            Pilih Semua
                        </button>
                        <button type="button" onclick="clearAllPermissions()" class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded hover:bg-gray-200">
                            Bersihkan Semua
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permissions as $permission)
                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition">
                        <input type="checkbox"
                               name="permissions[]"
                               value="{{ $permission->id }}"
                               class="permission-checkbox mt-1"
                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $permission->name }}</div>
                            <div class="text-sm text-gray-600">{{ $permission->description ?? 'Tanpa deskripsi' }}</div>
                        </div>
                    </label>
                @endforeach
            </div>

            @if($permissions->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <span class="material-symbols-outlined text-5xl opacity-30 block mb-2">lock</span>
                    <p>Belum ada permission yang dibuat</p>
                    <a href="{{ route('permissions.create') }}" class="text-primary hover:underline">
                        Buat permission baru
                    </a>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">save</span>
                Simpan Permission
            </button>
            <a href="{{ route('roles.index') }}"
               class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                <span class="material-symbols-outlined">cancel</span>
                Batal
            </a>
        </div>

        {{-- Summary --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-sm text-blue-900">
                <strong>Total Permission Dipilih:</strong>
                <span id="permissionCount" class="font-bold">{{ count($rolePermissions) }}</span>
            </p>
        </div>
    </form>
</div>

<script>
    function selectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        updatePermissionCount();
    }

    function clearAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updatePermissionCount();
    }

    function updatePermissionCount() {
        const count = document.querySelectorAll('.permission-checkbox:checked').length;
        document.getElementById('permissionCount').textContent = count;
    }

    // Update count when checkboxes change
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updatePermissionCount);
    });
</script>
@endsection
