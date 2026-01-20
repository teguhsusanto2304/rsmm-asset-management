@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Manajemen Role</h1>
            <p class="text-sm sm:text-base text-[#617989]">Kelola role dan permission sistem</p>
        </div>
        <a href="{{ route('roles.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 whitespace-nowrap">
            <span class="material-symbols-outlined">add</span>
            Tambah Role
        </a>
    </div>

    <x-alert />

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-3 sm:p-4 mb-6">
        <form method="GET" action="{{ route('roles.index') }}" class="flex flex-col sm:flex-row gap-2">
            <input type="text"
                   name="search"
                   placeholder="Cari role..."
                   value="{{ request('search') }}"
                   class="flex-1 form-input rounded-lg text-sm">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">search</span>
                <span class="sm:hidden">Cari</span>
            </button>
            <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">refresh</span>
                <span class="sm:hidden">Ulang</span>
            </a>
        </form>
    </div>

    {{-- Roles Table --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] overflow-x-auto">
        <table class="w-full min-w-max text-sm sm:text-base">
            <thead>
                <tr class="border-b border-[#dbe1e6] bg-gray-50">
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 w-1/4">Role</th>
                    <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Deskripsi</th>
                    <th class="px-3 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700">Perm</th>
                    <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700">User</th>
                    <th class="px-3 sm:px-6 py-3 text-right text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#dbe1e6]">
                @forelse($roles as $role)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 sm:px-6 py-4">
                            <div class="font-semibold text-gray-900 text-sm">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</div>
                        </td>
                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            {{ $role->description ?? '-' }}
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell px-3 sm:px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $role->users_count }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <div class="flex justify-end gap-1 sm:gap-2 flex-wrap">
                                <a href="{{ route('roles.show-permissions', $role) }}"
                                   class="text-purple-600 hover:bg-purple-100 p-1 rounded"
                                   title="Kelola Permission">
                                    <span class="material-symbols-outlined text-base sm:text-lg">admin_panel_settings</span>
                                </a>
                                <a href="{{ route('roles.edit', $role) }}"
                                   class="text-primary hover:bg-primary/10 p-1 rounded"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                                </a>
                                <form method="POST" action="{{ route('roles.destroy', $role) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-100 p-1 rounded" title="Hapus"
                                            onclick="return confirm('Hapus role ini?')">
                                        <span class="material-symbols-outlined text-base sm:text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 sm:px-6 py-8 text-center text-[#617989] text-sm">
                            Belum ada role terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $roles->withQueryString()->links() }}
    </div>
</div>
@endsection
