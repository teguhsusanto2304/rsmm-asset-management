@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Pekerjaan Saya</h1>
            <p class="text-sm sm:text-base text-[#617989]">Daftar lengkap pemeliharaan yang ditugaskan</p>
        </div>
    </div>

    <x-alert />

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-3 sm:p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text"
                       name="search"
                       placeholder="Cari nomor referensi atau aset..."
                       value="{{ $search }}"
                       class="w-full form-input rounded-lg text-sm">
            </div>
            <select name="status" class="form-input rounded-lg text-sm">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>Ditugaskan</option>
                <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 whitespace-nowrap">
                <span class="material-symbols-outlined inline mr-1">search</span>
                Cari
            </button>
            <a href="{{ url('/master-data/technician/maintenance') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300 whitespace-nowrap text-center">
                Reset
            </a>
        </form>
    </div>

    {{-- Maintenance Table --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] overflow-x-auto">
        <table class="w-full min-w-max text-sm sm:text-base">
            <thead>
                <tr class="border-b border-[#dbe1e6] bg-gray-50">
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Referensi</th>
                    <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Aset</th>
                    <th class="px-3 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700">Prioritas</th>
                    <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-center text-xs font-semibold text-gray-700">Status</th>
                    <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Dilaporkan</th>
                    <th class="px-3 sm:px-6 py-3 text-right text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#dbe1e6]">
                @forelse($maintenance as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 sm:px-6 py-4">
                            <div class="font-semibold text-gray-900 text-sm">{{ $item->reference_number }}</div>
                            <div class="text-xs text-gray-600">{{ $item->reported_date->format('d M') }}</div>
                        </td>
                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                            <div class="font-semibold text-gray-900 text-sm truncate">{{ $item->asset->name }}</div>
                            <div class="text-xs text-gray-600">{{ $item->asset->barcode }}</div>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold {{ $item->getPriorityColor() }}">
                                {{ $item->getPriorityLabel() }}
                            </span>
                        </td>
                        <td class="hidden md:table-cell px-3 sm:px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold {{ $item->getStatusColor() }}">
                                {{ $item->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="hidden lg:table-cell px-3 sm:px-6 py-4 text-sm">
                            <p class="font-semibold">{{ $item->reportedByUser->name }}</p>
                            <p class="text-xs text-gray-600">{{ $item->reported_date->format('d M Y H:i') }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <div class="flex justify-end gap-1 sm:gap-2">
                                <a href="{{ url('/master-data/maintenance/' . $item->id) }}"
                                   class="text-primary hover:bg-primary/10 p-1 rounded"
                                   title="Lihat Detail">
                                    <span class="material-symbols-outlined text-base sm:text-lg">visibility</span>
                                </a>
                                @if($item->status === 'assigned')
                                    <form method="POST" action="{{ url('/master-data/maintenance/' . $item->id . '/start') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:bg-yellow-100 p-1 rounded" title="Mulai Mengerjakan">
                                            <span class="material-symbols-outlined text-base sm:text-lg">play_arrow</span>
                                        </button>
                                    </form>
                                @elseif($item->status === 'in_progress')
                                    <button type="button"
                                            onclick="document.getElementById('completeModal{{ $item->id }}').classList.remove('hidden')"
                                            class="text-green-600 hover:bg-green-100 p-1 rounded" title="Laporkan Selesai">
                                        <span class="material-symbols-outlined text-base sm:text-lg">check</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Complete Modal --}}
                    @if($item->status === 'in_progress')
                        <div id="completeModal{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-y-auto">
                            <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 my-8">
                                <h2 class="text-xl font-bold mb-4">Laporkan Pemeliharaan Selesai</h2>
                                <p class="text-sm text-gray-600 mb-4">{{ $item->reference_number }} - {{ $item->asset->name }}</p>
                                <form method="POST" action="{{ url('/master-data/maintenance/' . $item->id . '/complete') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Pekerjaan yang Dilakukan <span class="text-red-500">*</span></label>
                                        <textarea name="work_performed" rows="3" class="w-full form-input rounded-lg" required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Suku Cadang yang Diganti</label>
                                        <textarea name="parts_replaced" rows="2" class="w-full form-input rounded-lg"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Jam Kerja Aktual <span class="text-red-500">*</span></label>
                                            <input type="number" name="actual_hours" min="1" max="100" class="w-full form-input rounded-lg" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Biaya Tenaga Kerja <span class="text-red-500">*</span></label>
                                            <input type="number" name="labor_cost" step="0.01" min="0" class="w-full form-input rounded-lg" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Biaya Suku Cadang</label>
                                        <input type="number" name="parts_cost" step="0.01" min="0" class="w-full form-input rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Catatan untuk Pemeliharaan Berikutnya</label>
                                        <textarea name="next_maintenance_notes" rows="2" class="w-full form-input rounded-lg"></textarea>
                                    </div>
                                    <div class="flex gap-2 justify-end pt-4 border-t">
                                        <button type="button" 
                                                onclick="document.getElementById('completeModal{{ $item->id }}').classList.add('hidden')"
                                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600">
                                            Lapor Selesai
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-3 sm:px-6 py-8 text-center text-gray-600">
                            Tidak ada pekerjaan ditemukan
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
