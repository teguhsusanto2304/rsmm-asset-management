@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Permintaan Transfer Asset</h1>
            <p class="text-[#617989]">Kelola permintaan peminjaman dan perpindahan asset</p>
        </div>
        <a href="{{ route('asset-transfers.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
            <span class="material-symbols-outlined">add</span>
            Buat Permintaan
        </a>
    </div>

    <x-alert />

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 mb-6">
        <form method="GET" action="{{ route('asset-transfers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium mb-2">Status</label>
                <select name="status" class="w-full form-input rounded-lg">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Type Filter --}}
            <div>
                <label class="block text-sm font-medium mb-2">Jenis</label>
                <select name="type" class="w-full form-input rounded-lg">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quick Filters --}}
            <div>
                <label class="block text-sm font-medium mb-2">Filter Cepat</label>
                <select name="quick_filter" class="w-full form-input rounded-lg">
                    <option value="">-- Pilihan --</option>
                    <option value="my_requests" {{ request('my_requests') ? 'selected' : '' }}>Permintaan Saya</option>
                    <option value="for_me" {{ request('for_me') ? 'selected' : '' }}>Untuk Saya</option>
                    <option value="from_my_assets" {{ request('from_my_assets') ? 'selected' : '' }}>Dari Asset Saya</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                    <span class="material-symbols-outlined inline mr-1">search</span>
                    Filter
                </button>
                <a href="{{ route('asset-transfers.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                    <span class="material-symbols-outlined">refresh</span>
                </a>
            </div>
        </form>
    </div>

    {{-- Transfer Requests Table --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#dbe1e6] bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Dari → Ke</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#dbe1e6]">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $request->asset->name }}</div>
                            <div class="text-sm text-gray-500">{{ $request->asset->barcode }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $request->type == 'borrow' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $request->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium">{{ $request->requestedFromUser->name }}</div>
                                <div class="text-gray-500">↓</div>
                                <div class="font-medium">{{ $request->requestedToUser->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $request->getStatusBadgeClass() }}">
                                {{ $request->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900">{{ $request->request_date->format('d/m/Y') }}</div>
                            <div class="text-gray-500">Dibuat: {{ $request->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('asset-transfers.show', $request) }}"
                               class="text-primary hover:bg-primary/10 p-1 rounded inline-flex"
                               title="Detail">
                                <span class="material-symbols-outlined text-base">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-[#617989]">
                            <div class="text-lg font-semibold mb-2">Tidak ada permintaan transfer</div>
                            <p>Mulai buat permintaan transfer atau pinjam asset dari pengguna lain</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $requests->withQueryString()->links() }}
    </div>
</div>
@endsection
