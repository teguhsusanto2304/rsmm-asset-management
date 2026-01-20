@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Dashboard Teknisi</h1>
            <p class="text-sm sm:text-base text-[#617989]">Kelola pekerjaan pemeliharaan aset yang ditugaskan</p>
        </div>
    </div>

    <x-alert />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Total Pekerjaan</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Menunggu Dikerjakan</p>
            <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Sedang Dikerjakan</p>
            <p class="text-lg sm:text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-[#dbe1e6] p-4">
            <p class="text-xs sm:text-sm text-gray-600">Selesai Bulan Ini</p>
            <p class="text-lg sm:text-2xl font-bold text-green-600">{{ $stats['completed_month'] }}</p>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white border-b border-[#dbe1e6] mb-6" x-data="{ activeTab: 'assigned' }">
        <div class="flex overflow-x-auto px-4 sm:px-6">
            <button @click="activeTab = 'assigned'" 
                    :class="activeTab === 'assigned' ? 'border-b-2 border-primary text-primary' : 'text-gray-600'"
                    class="py-4 px-4 font-semibold text-sm whitespace-nowrap transition">
                <span class="material-symbols-outlined inline mr-2 text-lg">assignment</span>
                Ditugaskan
            </button>
            <button @click="activeTab = 'in_progress'" 
                    :class="activeTab === 'in_progress' ? 'border-b-2 border-primary text-primary' : 'text-gray-600'"
                    class="py-4 px-4 font-semibold text-sm whitespace-nowrap transition">
                <span class="material-symbols-outlined inline mr-2 text-lg">build</span>
                Sedang Dikerjakan
            </button>
            <button @click="activeTab = 'completed'" 
                    :class="activeTab === 'completed' ? 'border-b-2 border-primary text-primary' : 'text-gray-600'"
                    class="py-4 px-4 font-semibold text-sm whitespace-nowrap transition">
                <span class="material-symbols-outlined inline mr-2 text-lg">check_circle</span>
                Selesai
            </button>
        </div>
    </div>

    {{-- Assigned Tab --}}
    <div x-show="activeTab === 'assigned'" class="space-y-4">
        @forelse($assigned as $maintenance)
            <div class="bg-white rounded-xl border border-[#dbe1e6] hover:shadow-md transition p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start mb-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Nomor Referensi</p>
                        <p class="font-bold text-gray-900">{{ $maintenance->reference_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Aset</p>
                        <p class="font-semibold text-gray-900">{{ $maintenance->asset->name }}</p>
                        <p class="text-xs text-gray-600">{{ $maintenance->asset->barcode }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Prioritas</p>
                        <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold {{ $maintenance->getPriorityColor() }}">
                            {{ $maintenance->getPriorityLabel() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Dilaporkan</p>
                        <p class="text-xs sm:text-sm font-semibold">{{ $maintenance->reported_date->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-2">Masalah:</p>
                    <p class="text-sm text-gray-600">{{ Str::limit($maintenance->issue_description, 150) }}</p>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <a href="{{ url('/master-data/maintenance/' . $maintenance->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                        <span class="material-symbols-outlined text-base">visibility</span>
                        Lihat Detail
                    </a>
                    <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/start') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-bold hover:bg-yellow-600">
                            <span class="material-symbols-outlined text-base">play_arrow</span>
                            Mulai Mengerjakan
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-8 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-300 block mb-3">checklist</span>
                <p class="text-gray-600">Tidak ada pekerjaan yang ditugaskan</p>
            </div>
        @endforelse
    </div>

    {{-- In Progress Tab --}}
    <div x-show="activeTab === 'in_progress'" class="space-y-4">
        @forelse($inProgress as $maintenance)
            <div class="bg-white rounded-xl border border-[#dbe1e6] hover:shadow-md transition p-4 sm:p-6 border-l-4 border-l-yellow-500">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start mb-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Nomor Referensi</p>
                        <p class="font-bold text-gray-900">{{ $maintenance->reference_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Aset</p>
                        <p class="font-semibold text-gray-900">{{ $maintenance->asset->name }}</p>
                        <p class="text-xs text-gray-600">{{ $maintenance->asset->barcode }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Waktu Dikerjakan</p>
                        <p class="text-xs sm:text-sm font-semibold">
                            @if($maintenance->started_date)
                                {{ now()->diffInHours($maintenance->started_date) }} jam yang lalu
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Est. Waktu</p>
                        <p class="text-xs sm:text-sm font-semibold">{{ $maintenance->estimated_hours ?? '-' }} jam</p>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4 mb-4 border border-yellow-200">
                    <p class="text-xs sm:text-sm font-semibold text-yellow-900 mb-2">Masalah:</p>
                    <p class="text-sm text-yellow-800">{{ $maintenance->issue_description }}</p>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <a href="{{ url('/master-data/maintenance/' . $maintenance->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                        <span class="material-symbols-outlined text-base">visibility</span>
                        Lihat Detail
                    </a>
                    <button type="button"
                            onclick="document.getElementById('completeModal{{ $maintenance->id }}').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-bold hover:bg-green-600">
                        <span class="material-symbols-outlined text-base">check</span>
                        Laporkan Selesai
                    </button>
                </div>
            </div>

            {{-- Complete Modal --}}
            <div id="completeModal{{ $maintenance->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-y-auto">
                <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 my-8">
                    <h2 class="text-xl font-bold mb-4">Laporkan Pemeliharaan Selesai</h2>
                    <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/complete') }}" class="space-y-4">
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
                                    onclick="document.getElementById('completeModal{{ $maintenance->id }}').classList.add('hidden')"
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
        @empty
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-8 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-300 block mb-3">build</span>
                <p class="text-gray-600">Tidak ada pekerjaan yang sedang dikerjakan</p>
            </div>
        @endforelse
    </div>

    {{-- Completed Tab --}}
    <div x-show="activeTab === 'completed'" class="space-y-4">
        @forelse($completed as $maintenance)
            <div class="bg-white rounded-xl border border-[#dbe1e6] hover:shadow-md transition p-4 sm:p-6 border-l-4 border-l-green-500">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start mb-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Nomor Referensi</p>
                        <p class="font-bold text-gray-900">{{ $maintenance->reference_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Aset</p>
                        <p class="font-semibold text-gray-900">{{ $maintenance->asset->name }}</p>
                        <p class="text-xs text-gray-600">{{ $maintenance->asset->barcode }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Selesai Pada</p>
                        <p class="text-xs sm:text-sm font-semibold">{{ $maintenance->completed_date->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Biaya</p>
                        <p class="text-sm sm:text-base font-bold text-green-600">Rp {{ number_format($maintenance->total_cost ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                        <p class="text-xs font-semibold text-green-900 mb-1">Jam Kerja</p>
                        <p class="text-sm text-green-800">{{ $maintenance->actual_hours }} jam</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <p class="text-xs font-semibold text-blue-900 mb-1">Biaya Tenaga Kerja</p>
                        <p class="text-sm text-blue-800">Rp {{ number_format($maintenance->labor_cost ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <a href="{{ url('/master-data/maintenance/' . $maintenance->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                        <span class="material-symbols-outlined text-base">visibility</span>
                        Lihat Laporan
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-8 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-300 block mb-3">check_circle</span>
                <p class="text-gray-600">Belum ada pekerjaan yang diselesaikan</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
