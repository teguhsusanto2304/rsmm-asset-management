@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Jadwal Pemeliharaan</h1>
            <p class="text-sm sm:text-base text-[#617989]">Kelola jadwal pemeliharaan preventif aset</p>
        </div>
        <a href="{{ route('maintenance-schedule.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 whitespace-nowrap flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Buat Jadwal
        </a>
    </div>

    <x-alert />

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-3 sm:p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text"
                       name="search"
                       placeholder="Cari nama jadwal atau aset..."
                       value="{{ $search }}"
                       class="w-full form-input rounded-lg text-sm">
            </div>
            <select name="status" class="form-input rounded-lg text-sm">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="paused" {{ $status === 'paused' ? 'selected' : '' }}>Ditunda</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 whitespace-nowrap">
                <span class="material-symbols-outlined inline mr-1">search</span>
                Cari
            </button>
            <a href="{{ route('maintenance-schedule.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300 whitespace-nowrap text-center">
                Reset
            </a>
        </form>
    </div>

    {{-- Schedules Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($schedules as $schedule)
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 hover:shadow-lg transition">
                {{-- Header --}}
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 line-clamp-2">{{ $schedule->name }}</h3>
                        <p class="text-xs text-gray-600 mt-1">{{ $schedule->asset->name }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $schedule->getStatusColor() }}">
                        {{ $schedule->getStatusLabel() }}
                    </span>
                </div>

                {{-- Details --}}
                <div class="space-y-2 text-sm mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Frekuensi:</span>
                        <span class="font-semibold">{{ $schedule->getFrequencyLabel() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Prioritas:</span>
                        <span class="font-semibold {{ str_contains($schedule->getPriorityColor(), 'red') ? 'text-red-600' : (str_contains($schedule->getPriorityColor(), 'orange') ? 'text-orange-600' : (str_contains($schedule->getPriorityColor(), 'yellow') ? 'text-yellow-600' : 'text-green-600')) }}">
                            {{ $schedule->getPriorityLabel() }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jadwal Berikutnya:</span>
                        <span class="font-semibold {{ $schedule->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $schedule->next_scheduled_date->format('d M Y') }}
                            @if($schedule->isOverdue())
                                <span class="text-xs text-red-600">Tertunda</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimasi Biaya:</span>
                        <span class="font-semibold">Rp {{ number_format($schedule->estimated_cost, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <a href="{{ route('maintenance-schedule.show', $schedule) }}" class="flex-1 px-3 py-2 bg-primary/10 text-primary rounded-lg text-sm font-bold hover:bg-primary/20 text-center">
                        <span class="material-symbols-outlined inline text-sm">visibility</span>
                    </a>
                    <a href="{{ route('maintenance-schedule.edit', $schedule) }}" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-bold hover:bg-blue-200 text-center">
                        <span class="material-symbols-outlined inline text-sm">edit</span>
                    </a>
                    @if($schedule->status === 'active')
                        <form method="POST" action="{{ route('maintenance-schedule.pause', $schedule) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-bold hover:bg-yellow-200">
                                <span class="material-symbols-outlined inline text-sm">pause</span>
                            </button>
                        </form>
                    @elseif($schedule->status === 'paused')
                        <form method="POST" action="{{ route('maintenance-schedule.resume', $schedule) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-bold hover:bg-green-200">
                                <span class="material-symbols-outlined inline text-sm">play_arrow</span>
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('maintenance-schedule.destroy', $schedule) }}" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-bold hover:bg-red-200">
                            <span class="material-symbols-outlined inline text-sm">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-2">
                    <span class="material-symbols-outlined text-5xl block">event_note</span>
                </div>
                <p class="text-gray-600">Belum ada jadwal pemeliharaan</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $schedules->withQueryString()->links() }}
    </div>
</div>
@endsection
