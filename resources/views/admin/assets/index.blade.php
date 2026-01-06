@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Daftar Asset</h1>
            <p class="text-[#617989]">Kelola semua asset yang terdaftar di sistem.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('assets.import') }}"
               class="flex items-center gap-2 rounded-lg h-10 px-4 bg-green-600 text-white text-sm font-bold hover:bg-green-700">
                <span class="material-symbols-outlined">upload_file</span>
                Import Asset
            </a>
            <a href="{{ route('assets.create') }}"
               class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">add</span>
                Tambah Asset
            </a>
        </div>
    </div>

    <x-alert />

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
        {{-- Search Bar --}}
        <form method="GET" class="mb-4 flex gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama, barcode, asset tag, atau serial number"
                   class="form-input w-full rounded-lg" />
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold">
                Cari
            </button>
        </form>

        {{-- Expandable Filter Section --}}
        <div x-data="{ open: {{ request()->hasAny(['category_id', 'department_id', 'location_id', 'status', 'condition', 'assigned_to']) ? 'true' : 'false' }} }" class="mb-4">
            <button @click="open = !open" 
                    type="button"
                    class="flex items-center justify-between w-full px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">filter_list</span>
                    <span class="font-semibold text-sm">Filter Lanjutan</span>
                </div>
                <span class="material-symbols-outlined text-lg transition-transform"
                      :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>

            <div x-show="open" 
                 x-collapse
                 class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <form method="GET" action="{{ route('assets.index') }}" class="space-y-4">
                    {{-- Preserve search parameter --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        {{-- Category Filter --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori</label>
                            <select name="category_id" class="form-input w-full rounded-lg text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Department Filter --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Departemen</label>
                            <select name="department_id" class="form-input w-full rounded-lg text-sm">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location Filter --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi</label>
                            <select name="location_id" class="form-input w-full rounded-lg text-sm">
                                <option value="">Semua Lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                            <select name="status" class="form-input w-full rounded-lg text-sm">
                                <option value="">Semua Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                                <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                        </div>

                        {{-- Condition Filter --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kondisi</label>
                            <select name="condition" class="form-input w-full rounded-lg text-sm">
                                <option value="">Semua Kondisi</option>
                                <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                <option value="critical" {{ request('condition') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        {{-- Assigned To Filter --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Ditugaskan Kepada</label>
                            <select name="assigned_to" class="form-input w-full rounded-lg text-sm">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter Actions --}}
                    <div class="flex gap-2 pt-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                            <span class="material-symbols-outlined align-middle text-base mr-1">search</span>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('assets.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                            <span class="material-symbols-outlined align-middle text-base mr-1">refresh</span>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f8f9fa] text-xs uppercase text-[#617989]">
                    <tr>
                        <th class="p-3 text-left">Asset</th>
                        <th class="p-3 text-left">Barcode</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Lokasi</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-[#f8fbff]">
                            <td class="p-3">
                                <div class="font-semibold text-[#111518]">{{ $asset->name }}</div>
                                <div class="text-xs text-[#617989]">
                                    SN: {{ $asset->serial_number }} |
                                    Tag: {{ $asset->asset_tag ?? '-' }}
                                </div>
                            </td>
                            <td class="p-3 font-mono text-xs">
                                {{ $asset->barcode }}
                            </td>
                            <td class="p-3">
                                {{ optional($asset->category)->name ?? '-' }}
                            </td>
                            <td class="p-3">
                                {{ optional($asset->location)->name ?? '-' }}
                            </td>
                            <td class="p-3">
                                @php
                                    $statusClasses = [
                                        'available' => 'bg-green-50 text-green-700',
                                        'assigned' => 'bg-blue-50 text-blue-700',
                                        'maintenance' => 'bg-yellow-50 text-yellow-700',
                                        'disposed' => 'bg-gray-100 text-gray-700',
                                        'reserved' => 'bg-purple-50 text-purple-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClasses[$asset->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($asset->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('assets.show', $asset) }}"
                                       class="text-gray-600 hover:bg-gray-100 p-1 rounded"
                                       title="Detail">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('assets.edit', $asset) }}"
                                       class="text-primary hover:bg-primary/10 p-1 rounded"
                                       title="Edit">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#617989]">
                                Belum ada asset terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $assets->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection


