@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Pemeliharaan Aset</h1>
            <p class="text-sm sm:text-base text-[#617989]">Kelola laporan pemeliharaan dan perbaikan aset</p>
        </div>
        <a href="{{ url('/master-data/maintenance/create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 whitespace-nowrap">
            <span class="material-symbols-outlined">add</span>
            Lapor Pemeliharaan
        </a>
    </div>

    <x-alert />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Total Laporan</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-800">{{ $maintenance->total() }}</p>
        </div>
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Proses</p>
            <p class="text-lg sm:text-2xl font-bold text-yellow-600">{{ $maintenance->getCollection()->whereIn('status', ['reported', 'assigned', 'in_progress'])->count() }}</p>
        </div>
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Selesai</p>
            <p class="text-lg sm:text-2xl font-bold text-green-600">{{ $maintenance->getCollection()->where('status', 'completed')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Dibatalkan</p>
            <p class="text-lg sm:text-2xl font-bold text-red-600">{{ $maintenance->getCollection()->where('status', 'cancelled')->count() }}</p>
        </div>
    </div>

    {{-- Maintenance List --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] overflow-x-auto">
        <table class="w-full min-w-max text-sm sm:text-base">
            <thead>
                <tr class="border-b border-[#dbe1e6] bg-gray-50">
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Ref. No.</th>
                    <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Aset</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Tipe</th>
                    <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700">Prioritas</th>
                    <th class="px-3 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700">Status</th>
                    <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Teknisi</th>
                    <th class="px-3 sm:px-6 py-3 text-right text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#dbe1e6]">
                @forelse($maintenance as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 sm:px-6 py-4">
                            <div class="font-semibold text-gray-900 text-sm">{{ $item->reference_number }}</div>
                            <div class="text-xs text-gray-600">{{ $item->reported_date->format('d M Y') }}</div>
                        </td>
                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                            <div class="font-semibold text-gray-900 text-sm truncate">{{ $item->asset->name }}</div>
                            <div class="text-xs text-gray-600">{{ $item->asset->barcode }}</div>
                        </td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="text-sm font-medium">{{ $item->getTypeLabel() }}</span>
                        </td>
                        <td class="hidden md:table-cell px-3 sm:px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold {{ $item->getPriorityColor() }}">
                                {{ $item->getPriorityLabel() }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold {{ $item->getStatusColor() }}">
                                {{ $item->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="hidden lg:table-cell px-3 sm:px-6 py-4">
                            <div class="text-sm">
                                {{ $item->technician?->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <div class="flex justify-end gap-1 sm:gap-2 flex-wrap">
                                <a href="{{ url('/master-data/maintenance/' . $item->id) }}"
                                   class="text-primary hover:bg-primary/10 p-1 rounded"
                                   title="Lihat Detail">
                                    <span class="material-symbols-outlined text-base sm:text-lg">visibility</span>
                                </a>
                                @if($item->canBeEdited() && auth()->user()->id === $item->reported_by)
                                    <a href="{{ url('/master-data/maintenance/' . $item->id . '/edit') }}"
                                       class="text-blue-600 hover:bg-blue-100 p-1 rounded"
                                       title="Edit">
                                        <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 sm:px-6 py-8 text-center text-[#617989] text-sm">
                            Belum ada laporan pemeliharaan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $maintenance->withQueryString()->links() }}
    </div>
</div>
@endsection
