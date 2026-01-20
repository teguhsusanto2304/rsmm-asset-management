@extends('layouts.admin.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Back Button --}}
    <a href="{{ url('/master-data/technician/maintenance') }}" class="flex items-center text-primary hover:text-primary/80 mb-6">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Header Card --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">{{ $maintenance->reference_number }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Dilaporkan pada {{ $maintenance->reported_date->format('d M Y H:i') }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $maintenance->getStatusColor() }}">
                        {{ $maintenance->getStatusLabel() }}
                    </span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-[#dbe1e6] pt-4">
                    <div>
                        <p class="text-xs text-gray-600 font-medium">Prioritas</p>
                        <p class="text-sm font-bold {{ str_contains($maintenance->getPriorityColor(), 'red') ? 'text-red-600' : (str_contains($maintenance->getPriorityColor(), 'orange') ? 'text-orange-600' : (str_contains($maintenance->getPriorityColor(), 'yellow') ? 'text-yellow-600' : 'text-green-600')) }}">
                            {{ $maintenance->getPriorityLabel() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-medium">Jenis Pemeliharaan</p>
                        <p class="text-sm font-bold">{{ ucfirst($maintenance->type) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-medium">Durasi</p>
                        <p class="text-sm font-bold">
                            @if($maintenance->status === 'completed')
                                {{ $maintenance->completed_date?->diffInHours($maintenance->started_date) ?? '0' }} jam
                            @elseif($maintenance->status === 'in_progress')
                                {{ now()->diffInHours($maintenance->started_date) }} jam
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Asset Information --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h2 class="text-lg font-bold text-[#111518] mb-4">Informasi Aset</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Aset</span>
                        <span class="font-semibold">{{ $maintenance->asset->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Barcode</span>
                        <span class="font-semibold">{{ $maintenance->asset->barcode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori</span>
                        <span class="font-semibold">{{ $maintenance->asset->category->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lokasi</span>
                        <span class="font-semibold">{{ $maintenance->asset->location->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kondisi</span>
                        <span class="font-semibold text-yellow-600">{{ ucfirst($maintenance->asset->condition) }}</span>
                    </div>
                </div>
            </div>

            {{-- Issue Report --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h2 class="text-lg font-bold text-[#111518] mb-4">Laporan Masalah</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Dipelapor Oleh</p>
                        <p class="text-base font-semibold">{{ $maintenance->reportedByUser->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Deskripsi Masalah</p>
                        <p class="text-base bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $maintenance->issue_description }}</p>
                    </div>
                </div>
            </div>

            {{-- Work Report (if completed) --}}
            @if($maintenance->status === 'completed')
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6 border-green-200 bg-green-50">
                    <h2 class="text-lg font-bold text-green-900 mb-4">Laporan Pekerjaan Selesai</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-green-800 font-medium mb-1">Pekerjaan yang Dilakukan</p>
                            <p class="text-base bg-white rounded-lg p-3 border border-green-200">{{ $maintenance->work_performed }}</p>
                        </div>
                        @if($maintenance->parts_replaced)
                            <div>
                                <p class="text-sm text-green-800 font-medium mb-1">Suku Cadang yang Diganti</p>
                                <p class="text-base bg-white rounded-lg p-3 border border-green-200">{{ $maintenance->parts_replaced }}</p>
                            </div>
                        @endif
                        @if($maintenance->next_maintenance_notes)
                            <div>
                                <p class="text-sm text-green-800 font-medium mb-1">Catatan untuk Pemeliharaan Berikutnya</p>
                                <p class="text-base bg-white rounded-lg p-3 border border-green-200">{{ $maintenance->next_maintenance_notes }}</p>
                            </div>
                        @endif
                        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-green-200">
                            <div>
                                <p class="text-xs text-green-800 font-medium">Jam Kerja</p>
                                <p class="text-sm font-bold">{{ $maintenance->actual_hours }} jam</p>
                            </div>
                            <div>
                                <p class="text-xs text-green-800 font-medium">Waktu Penyelesaian</p>
                                <p class="text-sm font-bold">{{ $maintenance->completed_date?->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Cost Summary --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-bold text-[#111518] mb-4">Ringkasan Biaya</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya Tenaga Kerja</span>
                        <span class="font-semibold">Rp {{ number_format($maintenance->labor_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya Suku Cadang</span>
                        <span class="font-semibold">Rp {{ number_format($maintenance->parts_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold border-t border-[#dbe1e6] pt-3">
                        <span>Total Biaya</span>
                        <span>Rp {{ number_format($maintenance->total_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-bold text-[#111518] mb-4">Timeline</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Dilaporkan</p>
                            <p class="text-gray-600 text-xs">{{ $maintenance->reported_date->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($maintenance->assigned_date)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-0.5 h-4 bg-gray-300"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Ditugaskan</p>
                                <p class="text-gray-600 text-xs">{{ $maintenance->assigned_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($maintenance->started_date)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-0.5 h-4 bg-gray-300"></div>
                                <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Dimulai</p>
                                <p class="text-gray-600 text-xs">{{ $maintenance->started_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($maintenance->completed_date)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-0.5 h-4 bg-gray-300"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Selesai</p>
                                <p class="text-gray-600 text-xs">{{ $maintenance->completed_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-bold text-[#111518] mb-4">Aksi</h3>
                <div class="space-y-2">
                    @if($maintenance->status === 'assigned')
                        <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/start') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition">
                                <span class="material-symbols-outlined inline mr-1 text-sm">play_arrow</span>
                                Mulai Mengerjakan
                            </button>
                        </form>
                    @elseif($maintenance->status === 'in_progress')
                        <button type="button"
                                onclick="document.getElementById('completeForm').classList.remove('hidden')"
                                class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition">
                            <span class="material-symbols-outlined inline mr-1 text-sm">check</span>
                            Laporkan Selesai
                        </button>
                    @endif
                    <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/cancel') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg font-bold hover:bg-red-600 transition" onclick="return confirm('Yakin ingin membatalkan pemeliharaan ini?')">
                            <span class="material-symbols-outlined inline mr-1 text-sm">close</span>
                            Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Complete Form (Hidden by Default) --}}
    @if($maintenance->status === 'in_progress')
        <div id="completeForm" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-y-auto mt-6">
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
                                onclick="document.getElementById('completeForm').classList.add('hidden')"
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
</div>
@endsection
