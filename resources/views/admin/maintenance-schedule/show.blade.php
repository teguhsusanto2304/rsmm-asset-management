@extends('layouts.admin.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <a href="{{ route('maintenance-schedule.index') }}" class="flex items-center text-primary hover:text-primary/80 mb-6">
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
                        <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">{{ $schedule->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $schedule->asset->name }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $schedule->getStatusColor() }}">
                        {{ $schedule->getStatusLabel() }}
                    </span>
                </div>

                @if($schedule->description)
                    <p class="text-gray-600">{{ $schedule->description }}</p>
                @endif
            </div>

            {{-- Schedule Details --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h2 class="text-lg font-bold text-[#111518] mb-4">Detail Jadwal</h2>
                <div class="space-y-3">
                    <div class="flex justify-between pb-3 border-b">
                        <span class="text-gray-600">Frekuensi</span>
                        <span class="font-semibold">{{ $schedule->getFrequencyLabel() }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b">
                        <span class="text-gray-600">Prioritas</span>
                        <span class="font-semibold">{{ $schedule->getPriorityLabel() }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b">
                        <span class="text-gray-600">Tanggal Mulai</span>
                        <span class="font-semibold">{{ $schedule->start_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b">
                        <span class="text-gray-600">Jadwal Berikutnya</span>
                        <span class="font-semibold {{ $schedule->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $schedule->next_scheduled_date->format('d M Y') }}
                            @if($schedule->isOverdue())
                                <span class="text-xs text-red-600 ml-2">(Tertunda)</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between pb-3 border-b">
                        <span class="text-gray-600">Terakhir Dijalankan</span>
                        <span class="font-semibold">
                            @if($schedule->last_executed_date)
                                {{ $schedule->last_executed_date->format('d M Y') }}
                            @else
                                <span class="text-gray-500">Belum pernah</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat Oleh</span>
                        <span class="font-semibold">{{ $schedule->creator->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Asset Information --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h2 class="text-lg font-bold text-[#111518] mb-4">Informasi Aset</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama</span>
                        <span class="font-semibold">{{ $schedule->asset->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Barcode</span>
                        <span class="font-semibold">{{ $schedule->asset->barcode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori</span>
                        <span class="font-semibold">{{ $schedule->asset->category->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lokasi</span>
                        <span class="font-semibold">{{ $schedule->asset->location->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Departemen</span>
                        <span class="font-semibold">{{ $schedule->asset->department->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Maintenance Notes --}}
            @if($schedule->maintenance_notes)
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-4 sm:p-6">
                    <h2 class="text-lg font-bold text-blue-900 mb-2">Catatan Pemeliharaan</h2>
                    <p class="text-blue-800 whitespace-pre-wrap">{{ $schedule->maintenance_notes }}</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Cost & Hours Summary --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-bold text-[#111518] mb-4">Estimasi Pemeliharaan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-600 font-medium">Biaya</p>
                        <p class="text-2xl font-black text-primary">Rp {{ number_format($schedule->estimated_cost, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-t pt-3">
                        <p class="text-xs text-gray-600 font-medium">Jam Kerja</p>
                        <p class="text-2xl font-black text-blue-600">{{ $schedule->estimated_hours }} Jam</p>
                    </div>
                </div>
            </div>

            {{-- Generated Maintenance Count --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-bold text-[#111518] mb-4">Pemeliharaan yang Dibuat</h3>
                <div class="text-center">
                    <p class="text-4xl font-black text-green-600">{{ $schedule->generatedMaintenance->count() }}</p>
                    <p class="text-sm text-gray-600 mt-2">pekerjaan pemeliharaan</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
                <h3 class="text-lg font-bold text-[#111518] mb-4">Aksi</h3>
                <div class="space-y-2">
                    <a href="{{ route('maintenance-schedule.edit', $schedule) }}" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-600 transition text-center block">
                        <span class="material-symbols-outlined inline mr-1 text-sm">edit</span>
                        Edit
                    </a>
                    @if($schedule->status === 'active')
                        <form method="POST" action="{{ route('maintenance-schedule.pause', $schedule) }}" class="block">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition">
                                <span class="material-symbols-outlined inline mr-1 text-sm">pause</span>
                                Tunda
                            </button>
                        </form>
                    @elseif($schedule->status === 'paused')
                        <form method="POST" action="{{ route('maintenance-schedule.resume', $schedule) }}" class="block">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-bold hover:bg-green-600 transition">
                                <span class="material-symbols-outlined inline mr-1 text-sm">play_arrow</span>
                                Lanjutkan
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('maintenance-schedule.destroy', $schedule) }}" class="block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg font-bold hover:bg-red-600 transition">
                            <span class="material-symbols-outlined inline mr-1 text-sm">delete</span>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Generated Maintenance List --}}
    @if($schedule->generatedMaintenance->count() > 0)
        <div class="mt-6 bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <h2 class="text-lg font-bold text-[#111518] mb-4">Riwayat Pemeliharaan yang Dibuat</h2>
            <div class="overflow-x-auto">
                <table class="w-full min-w-max text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="px-4 py-2 text-left">Referensi</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Dibuat</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($schedule->generatedMaintenance as $maintenance)
                            <tr>
                                <td class="px-4 py-2 font-semibold">{{ $maintenance->reference_number }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $maintenance->getStatusColor() }}">
                                        {{ $maintenance->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-xs text-gray-600">{{ $maintenance->reported_date->format('d M Y') }}</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ url('/master-data/maintenance/' . $maintenance->id) }}" class="text-primary hover:underline text-xs">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
