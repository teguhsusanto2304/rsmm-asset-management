@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Statistik Kinerja</h1>
        <p class="text-sm sm:text-base text-[#617989]">Ringkasan performa pekerjaan Anda</p>
    </div>

    {{-- Main Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        {{-- Total Jobs --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Pekerjaan</p>
                    <p class="text-3xl font-black text-[#111518] mt-1">{{ $totalJobs }}</p>
                    <p class="text-xs text-gray-500 mt-2">Sepanjang masa</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 text-2xl">assignment</span>
                </div>
            </div>
        </div>

        {{-- Pending Jobs --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Ditugaskan</p>
                    <p class="text-3xl font-black text-yellow-600 mt-1">{{ $assignedJobs }}</p>
                    <p class="text-xs text-gray-500 mt-2">Menunggu untuk dimulai</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600 text-2xl">schedule</span>
                </div>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Sedang Dikerjakan</p>
                    <p class="text-3xl font-black text-orange-600 mt-1">{{ $inProgressJobs }}</p>
                    <p class="text-xs text-gray-500 mt-2">Aktif sekarang</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-orange-600 text-2xl">build</span>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Selesai</p>
                    <p class="text-3xl font-black text-green-600 mt-1">{{ $completedJobs }}</p>
                    <p class="text-xs text-gray-500 mt-2">Bulan ini</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Performance Metrics --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <h2 class="text-lg font-bold text-[#111518] mb-4">Metrik Kinerja</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-700">Rata-rata Waktu Penyelesaian</p>
                        <p class="text-sm font-bold text-gray-900">{{ round($avgCompletionTime, 1) }} jam</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(($avgCompletionTime / 100) * 100, 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-700">Total Jam Kerja (Bulan Ini)</p>
                        <p class="text-sm font-bold text-gray-900">{{ $totalHoursThisMonth }} jam</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ min((($totalHoursThisMonth ?? 0) / 160) * 100, 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-700">Total Biaya Tenaga Kerja</p>
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($totalLaborCost ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ min((($totalLaborCost ?? 0) / 10000000) * 100, 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium text-gray-700">Total Biaya Suku Cadang</p>
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($totalPartsCost ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ min((($totalPartsCost ?? 0) / 10000000) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completion Rate Card --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <h2 class="text-lg font-bold text-[#111518] mb-4">Tingkat Penyelesaian</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-center">
                    <div class="relative w-24 h-24">
                        <svg class="transform -rotate-90 w-24 h-24" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8" />
                            <circle cx="50" cy="50" r="45" fill="none" stroke="#10b981" stroke-width="8"
                                    stroke-dasharray="{{ $completionRate * 2.827 }} 282.7"
                                    stroke-linecap="round" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-black text-green-600">{{ round($completionRate) }}%</span>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 text-center">{{ $completedJobs }} dari {{ $totalJobs }} pekerjaan selesai</p>
            </div>
        </div>
    </div>

    {{-- Monthly Chart --}}
    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
        <h2 class="text-lg font-bold text-[#111518] mb-4">Pekerjaan Per Bulan (6 Bulan Terakhir)</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full flex items-end gap-3" style="height: 300px">
                @foreach($monthlyStats as $month)
                    <div class="flex-1 flex flex-col items-center gap-2 min-w-16">
                        <div class="w-full bg-gray-200 rounded-t-lg relative" style="height: {{ max($month['completed'] * 20, 20) }}px">
                            <div class="w-full bg-green-500 rounded-t-lg" style="height: {{ $month['completed'] * 20 }}px"></div>
                        </div>
                        <p class="text-xs font-medium text-gray-700">{{ $month['month'] }}</p>
                        <p class="text-xs text-gray-600">{{ $month['completed'] }} pekerjaan</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Priority Distribution --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        {{-- Priority Distribution --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <h2 class="text-lg font-bold text-[#111518] mb-4">Distribusi Prioritas</h2>
            <div class="space-y-3">
                @php
                    $priorities = ['critical' => 'Kritis', 'high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'];
                    $priorityColors = ['critical' => 'bg-red-500', 'high' => 'bg-orange-500', 'medium' => 'bg-yellow-500', 'low' => 'bg-green-500'];
                @endphp
                @foreach($priorities as $key => $label)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold">{{ $priorityDistribution[$key] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $priorityColors[$key] }}" style="width: {{ $totalJobs > 0 ? (($priorityDistribution[$key] ?? 0) / $totalJobs) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Type Distribution --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
            <h2 class="text-lg font-bold text-[#111518] mb-4">Jenis Pemeliharaan</h2>
            <div class="space-y-3">
                @php
                    $types = ['corrective' => 'Perbaikan', 'preventive' => 'Pencegahan', 'emergency' => 'Darurat'];
                    $typeColors = ['corrective' => 'bg-blue-500', 'preventive' => 'bg-purple-500', 'emergency' => 'bg-red-500'];
                @endphp
                @foreach($types as $key => $label)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold">{{ $typeDistribution[$key] ?? 0 }} ({{ $totalJobs > 0 ? round((($typeDistribution[$key] ?? 0) / $totalJobs) * 100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $typeColors[$key] }}" style="width: {{ $totalJobs > 0 ? (($typeDistribution[$key] ?? 0) / $totalJobs) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
