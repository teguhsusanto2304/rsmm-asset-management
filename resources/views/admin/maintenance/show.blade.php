@extends('layouts.admin.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Detail Pemeliharaan</h1>
            <p class="text-sm sm:text-base text-[#617989]">{{ $maintenance->reference_number }}</p>
        </div>
        <a href="{{ url('/master-data/maintenance') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Status & Priority --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $maintenance->getStatusColor() }}">
                            {{ $maintenance->getStatusLabel() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Prioritas</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $maintenance->getPriorityColor() }}">
                            {{ $maintenance->getPriorityLabel() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Jenis</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                            {{ $maintenance->getTypeLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Asset Information --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Aset</h3>
                <div class="space-y-3">
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Nama Aset</span>
                        <span class="font-semibold">{{ $maintenance->asset->name }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Barcode</span>
                        <span class="font-mono text-sm">{{ $maintenance->asset->barcode }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Kategori</span>
                        <span class="font-semibold">{{ $maintenance->asset->category->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kondisi Saat Ini</span>
                        <span class="font-semibold">{{ ucfirst($maintenance->asset->condition) }}</span>
                    </div>
                </div>
            </div>

            {{-- Issue Description --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-semibold mb-4">Laporan Masalah</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Deskripsi Masalah</p>
                        <p class="text-gray-800">{{ $maintenance->issue_description }}</p>
                    </div>
                    @if($maintenance->symptoms)
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Gejala / Tanda-tanda</p>
                            <p class="text-gray-800">{{ $maintenance->symptoms }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Technician Report (if completed) --}}
            @if($maintenance->status === 'completed' && $maintenance->work_performed)
                <div class="bg-green-50 rounded-xl border border-green-200 p-4 sm:p-6">
                    <h3 class="text-lg font-semibold mb-4 text-green-900">Laporan Teknisi</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-green-800 mb-2">Pekerjaan yang Dilakukan</p>
                            <p class="text-gray-800">{{ $maintenance->work_performed }}</p>
                        </div>
                        @if($maintenance->parts_replaced)
                            <div>
                                <p class="text-sm font-medium text-green-800 mb-2">Suku Cadang yang Diganti</p>
                                <p class="text-gray-800">{{ $maintenance->parts_replaced }}</p>
                            </div>
                        @endif
                        @if($maintenance->next_maintenance_notes)
                            <div>
                                <p class="text-sm font-medium text-green-800 mb-2">Catatan untuk Pemeliharaan Berikutnya</p>
                                <p class="text-gray-800">{{ $maintenance->next_maintenance_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Timeline --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-semibold mb-4">Timeline</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 bg-primary rounded-full mt-1"></div>
                            <div class="w-0.5 h-12 bg-gray-300"></div>
                        </div>
                        <div>
                            <p class="font-medium">Dilaporkan</p>
                            <p class="text-gray-600 text-xs">{{ $maintenance->reported_date->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($maintenance->assigned_date)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mt-1"></div>
                                <div class="w-0.5 h-12 bg-gray-300"></div>
                            </div>
                            <div>
                                <p class="font-medium">Ditugaskan ke {{ $maintenance->technician?->name }}</p>
                                <p class="text-gray-600 text-xs">{{ $maintenance->assigned_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($maintenance->started_date)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mt-1"></div>
                                <div class="w-0.5 h-12 bg-gray-300"></div>
                            </div>
                            <div>
                                <p class="font-medium">Mulai Dikerjakan</p>
                                <p class="text-gray-600 text-xs">{{ $maintenance->started_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($maintenance->completed_date)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                            </div>
                            <div>
                                <p class="font-medium">Selesai</p>
                                <p class="text-gray-600 text-xs">{{ $maintenance->completed_date->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- People --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-semibold mb-4">Orang-orang</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">Pelapor</p>
                        <p class="font-semibold">{{ $maintenance->reportedByUser->name }}</p>
                        <p class="text-gray-600 text-xs">{{ $maintenance->reportedByUser->email }}</p>
                    </div>
                    @if($maintenance->technician)
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-gray-600 mb-1">Teknisi</p>
                            <p class="font-semibold">{{ $maintenance->technician->name }}</p>
                            <p class="text-gray-600 text-xs">{{ $maintenance->technician->email }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Costs (if available) --}}
            @if($maintenance->status === 'completed')
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                    <h3 class="text-lg font-semibold mb-4">Biaya Pemeliharaan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between pb-2 border-b border-gray-200">
                            <span class="text-gray-600">Biaya Tenaga Kerja</span>
                            <span class="font-semibold">Rp {{ number_format($maintenance->labor_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pb-2 border-b border-gray-200">
                            <span class="text-gray-600">Biaya Suku Cadang</span>
                            <span class="font-semibold">Rp {{ number_format($maintenance->parts_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="text-gray-700 font-medium">Total Biaya</span>
                            <span class="font-bold text-primary">Rp {{ number_format($maintenance->total_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Work Hours --}}
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                    <h3 class="text-lg font-semibold mb-4">Waktu Kerja</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estimasi</span>
                            <span class="font-semibold">{{ $maintenance->estimated_hours ?? '-' }} jam</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Aktual</span>
                            <span class="font-semibold">{{ $maintenance->actual_hours ?? '-' }} jam</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-semibold mb-4">Aksi</h3>
                <div class="space-y-2">
                    @if($maintenance->canBeEdited() && auth()->user()->id === $maintenance->reported_by)
                        <a href="{{ url('/master-data/maintenance/' . $maintenance->id . '/edit') }}"
                           class="block w-full px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-bold hover:bg-blue-600">
                            Edit Laporan
                        </a>
                    @endif

                    @if(auth()->user()->hasRole(['admin', 'manager']) && $maintenance->status === 'reported')
                        <button class="w-full px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-bold hover:bg-green-600"
                                data-toggle="modal"
                                data-target="#assignModal">
                            Tugaskan ke Teknisi
                        </button>
                    @endif

                    @if(auth()->user()->id === $maintenance->technician_id && $maintenance->canBeStarted())
                        <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/start') }}" class="inline-block w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-bold hover:bg-yellow-600">
                                Mulai Mengerjakan
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->id === $maintenance->technician_id && $maintenance->canBeCompleted())
                        <button class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg text-sm font-bold hover:bg-purple-600"
                                data-toggle="modal"
                                data-target="#completeModal">
                            Laporkan Selesai
                        </button>
                    @endif

                    @if(in_array($maintenance->status, ['reported', 'assigned']))
                        <button class="w-full px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-bold hover:bg-red-600"
                                data-toggle="modal"
                                data-target="#cancelModal">
                            Batalkan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Assign Technician Modal --}}
@if(auth()->user()->hasRole(['admin', 'manager']) && $maintenance->status === 'reported')
    <div id="assignModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
         x-data="{ show: false }" @keydown.escape="show = false">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Tugaskan ke Teknisi</h2>
            <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/assign') }}">
                @csrf
                <div class="mb-4">
                    <label for="technician_id" class="block text-sm font-medium mb-2">Pilih Teknisi</label>
                    <select id="technician_id" name="technician_id" class="w-full form-input rounded-lg" required>
                        <option value="">-- Pilih Teknisi --</option>
                        <!-- Will be populated by JavaScript -->
                    </select>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold"
                            onclick="document.getElementById('assignModal').classList.add('hidden')">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90">
                        Tugaskan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Complete Maintenance Modal --}}
@if(auth()->user()->id === $maintenance->technician_id && $maintenance->canBeCompleted())
    <div id="completeModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 overflow-y-auto">
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
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold"
                            onclick="document.getElementById('completeModal').classList.add('hidden')">
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

{{-- Cancel Modal --}}
@if(in_array($maintenance->status, ['reported', 'assigned']))
    <div id="cancelModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Batalkan Pemeliharaan</h2>
            <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id . '/cancel') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
                    <textarea name="cancellation_reason" rows="3" class="w-full form-input rounded-lg" required></textarea>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold"
                            onclick="document.getElementById('cancelModal').classList.add('hidden')">
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg font-bold hover:bg-red-600">
                        Batalkan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<script>
    // Modal toggle
    document.querySelectorAll('[data-toggle="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            document.querySelector(targetId).classList.remove('hidden');
        });
    });

    // Close modal on outside click
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    @if(auth()->user()->hasRole(['admin', 'manager']) && $maintenance->status === 'reported')
        // Load technicians
        fetch('{{ url("/master-data/maintenance/api/technicians") }}')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('technician_id');
                data.forEach(tech => {
                    const option = document.createElement('option');
                    option.value = tech.id;
                    option.textContent = tech.name + ' (' + tech.email + ')';
                    select.appendChild(option);
                });
            });
    @endif
</script>
@endsection
