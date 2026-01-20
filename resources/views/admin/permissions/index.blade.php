@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Manajemen Permission</h1>
            <p class="text-[#617989]">Kelola permission sistem untuk access control</p>
        </div>
        <a href="{{ route('permissions.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
            <span class="material-symbols-outlined">add</span>
            Tambah Permission
        </a>
    </div>

    <x-alert />

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 mb-6">
        <form method="GET" action="{{ route('permissions.index') }}" class="flex gap-2">
            <input type="text"
                   name="search"
                   placeholder="Cari permission..."
                   value="{{ request('search') }}"
                   class="flex-1 form-input rounded-lg">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">search</span>
            </button>
            <a href="{{ route('permissions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                <span class="material-symbols-outlined">refresh</span>
            </a>
        </form>
    </div>

    {{-- Permissions Table --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#dbe1e6] bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 w-1/4">Permission</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Guard</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#dbe1e6]">
                @forelse($permissions as $permission)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-mono text-sm font-semibold text-gray-900">{{ $permission->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $permission->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $permission->guard_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('permissions.edit', $permission) }}"
                                   class="text-primary hover:bg-primary/10 p-1 rounded"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form method="POST" action="{{ route('permissions.destroy', $permission) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-100 p-1 rounded" title="Hapus"
                                            onclick="return confirm('Hapus permission ini?')">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-[#617989]">
                            Belum ada permission terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $permissions->withQueryString()->links() }}
    </div>
</div>
@endsection
