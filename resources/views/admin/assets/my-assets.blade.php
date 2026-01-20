@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Asset Saya</h1>
            <p class="text-[#617989]">Daftar lengkap asset yang ditugaskan kepada Anda</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('assets.index') }}"
               class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                <span class="material-symbols-outlined">list</span>
                Semua Asset
            </a>
        </div>
    </div>

    <x-alert />

    {{-- Search and Filter --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 mb-6">
        <form method="GET" action="{{ route('assets.my-assets') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <input type="text"
                           name="search"
                           placeholder="Cari nama, barcode, serial number..."
                           value="{{ request('search') }}"
                           class="w-full form-input rounded-lg">
                </div>

                {{-- Category Filter --}}
                <div>
                    <select name="category_id" class="w-full form-input rounded-lg">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <select name="status" class="w-full form-input rounded-lg">
                        <option value="">-- Semua Status --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Ditugaskan</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                        <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Pensiun</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Condition Filter --}}
                <div>
                    <select name="condition" class="w-full form-input rounded-lg">
                        <option value="">-- Semua Kondisi --</option>
                        <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Sangat Baik</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                        <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Cukup</option>
                        <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Buruk</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">search</span>
                        Cari
                    </button>
                    <a href="{{ route('assets.my-assets') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                        <span class="material-symbols-outlined">refresh</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Assets Table --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#dbe1e6] bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Barcode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#dbe1e6]">
                @forelse($assets as $asset)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $asset->name }}</div>
                            <div class="text-sm text-gray-500">{{ $asset->asset_tag ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $asset->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600">{{ $asset->barcode }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $asset->condition === 'excellent' ? 'bg-green-100 text-green-800' : 
                                   ($asset->condition === 'good' ? 'bg-blue-100 text-blue-800' :
                                   ($asset->condition === 'fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                {{ match($asset->condition) {
                                    'excellent' => 'Sangat Baik',
                                    'good' => 'Baik',
                                    'fair' => 'Cukup',
                                    'poor' => 'Buruk',
                                    default => ucfirst($asset->condition)
                                } }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $asset->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($asset->status === 'assigned' ? 'bg-blue-100 text-blue-800' :
                                   ($asset->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ match($asset->status) {
                                    'active' => 'Aktif',
                                    'assigned' => 'Ditugaskan',
                                    'maintenance' => 'Pemeliharaan',
                                    'retired' => 'Pensiun',
                                    default => ucfirst($asset->status)
                                } }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('assets.show', $asset) }}"
                                   class="text-gray-600 hover:bg-gray-100 p-1 rounded"
                                   title="Detail">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>
                                <a href="{{ route('asset-transfers.create', ['asset_id' => $asset->id]) }}"
                                   class="text-orange-600 hover:bg-orange-100 p-1 rounded"
                                   title="Pinjam/Pindah">
                                    <span class="material-symbols-outlined text-base">compare_arrows</span>
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
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-5xl text-gray-300">inbox</span>
                                <div class="text-lg font-semibold text-gray-600 mb-2">Tidak ada asset</div>
                                <p class="text-sm text-gray-500">Belum ada asset yang ditugaskan kepada Anda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $assets->withQueryString()->links() }}
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Asset</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $assets->total() }}</p>
                </div>
                <div class="text-3xl text-primary">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Kondisi Baik</p>
                    <p class="text-2xl font-bold text-green-600">{{ $assets->getCollection()->whereIn('condition', ['excellent', 'good'])->count() }}</p>
                </div>
                <div class="text-3xl text-green-500">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pemeliharaan</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $assets->getCollection()->where('status', 'maintenance')->count() }}</p>
                </div>
                <div class="text-3xl text-yellow-500">
                    <span class="material-symbols-outlined">build</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pensiun</p>
                    <p class="text-2xl font-bold text-red-600">{{ $assets->getCollection()->where('status', 'retired')->count() }}</p>
                </div>
                <div class="text-3xl text-red-500">
                    <span class="material-symbols-outlined">block</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
