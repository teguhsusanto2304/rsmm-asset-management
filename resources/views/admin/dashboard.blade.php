@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

<div class="layout-content-container flex w-full max-w-[1200px] flex-1 flex-col self-center">
<div class="flex flex-wrap justify-between gap-3 p-4">
<p class="text-gray-900 text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Dashboard</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
<div class="flex flex-col justify-between rounded-xl bg-green-500 p-6 shadow-md text-white transition-all hover:-translate-y-1">
<div class="flex justify-between items-start">
<div class="flex flex-col gap-1">
<p class="text-green-50 text-sm font-medium uppercase tracking-wider">Jumlah Total Aset</p>
<p class="text-4xl font-bold leading-tight mt-1">1,240</p>
</div>
<div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
<span class="material-symbols-outlined text-3xl">inventory_2</span>
</div>
</div>
<div class="mt-4 flex items-center gap-2 text-sm text-green-100 font-medium">
<span class="material-symbols-outlined text-lg">trending_up</span>
<span>+12 bulan ini</span>
</div>
</div>
<div class="flex flex-col justify-between rounded-xl bg-yellow-400 p-6 shadow-md text-gray-900 transition-all hover:-translate-y-1">
<div class="flex justify-between items-start">
<div class="flex flex-col gap-1">
<p class="text-gray-800/80 text-sm font-medium uppercase tracking-wider">Aset dengan Permohonan Mutasi</p>
<p class="text-4xl font-bold leading-tight mt-1">86</p>
</div>
<div class="p-3 bg-white/30 rounded-lg backdrop-blur-sm">
<span class="material-symbols-outlined text-3xl">move_up</span>
</div>
</div>
<div class="mt-4 flex items-center gap-2 text-sm text-gray-800/80 font-medium">
<span class="material-symbols-outlined text-lg">pending</span>
<span>5 perlu persetujuan</span>
</div>
</div>
<div class="flex flex-col justify-between rounded-xl bg-red-500 p-6 shadow-md text-white transition-all hover:-translate-y-1">
<div class="flex justify-between items-start">
<div class="flex flex-col gap-1">
<p class="text-red-50 text-sm font-medium uppercase tracking-wider">Aset dengan Permohonan Diperbaiki</p>
<p class="text-4xl font-bold leading-tight mt-1">15</p>
</div>
<div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
<span class="material-symbols-outlined text-3xl">handyman</span>
</div>
</div>
<div class="mt-4 flex items-center gap-2 text-sm text-red-100 font-medium">
<span class="material-symbols-outlined text-lg">warning</span>
<span>3 kondisi kritis</span>
</div>
</div>
</div>
<div class="p-4">
<div class="flex flex-col md:flex-row gap-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
<div class="flex-1 flex flex-col justify-center">
<h3 class="text-gray-900 text-lg font-bold leading-tight mb-2">Ringkasan Status Aset</h3>
<p class="text-gray-500 text-sm mb-6">Distribusi kondisi seluruh aset perusahaan secara real-time.</p>
<div class="space-y-5">
<div>
<div class="flex justify-between text-sm mb-1.5">
<span class="font-medium text-gray-700 flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-green-500"></span> Kondisi Baik
                        </span>
<span class="font-bold text-gray-900">75% (930)</span>
</div>
<div class="w-full bg-gray-50 rounded-full h-2.5 overflow-hidden">
<div class="bg-green-500 h-2.5 rounded-full" style="width: 75%"></div>
</div>
</div>
<div>
<div class="flex justify-between text-sm mb-1.5">
<span class="font-medium text-gray-700 flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-yellow-400"></span> Dalam Mutasi
                        </span>
<span class="font-bold text-gray-900">12% (149)</span>
</div>
<div class="w-full bg-gray-50 rounded-full h-2.5 overflow-hidden">
<div class="bg-yellow-400 h-2.5 rounded-full" style="width: 12%"></div>
</div>
</div>
<div>
<div class="flex justify-between text-sm mb-1.5">
<span class="font-medium text-gray-700 flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-red-500"></span> Dalam Perbaikan
                        </span>
<span class="font-bold text-gray-900">8% (99)</span>
</div>
<div class="w-full bg-gray-50 rounded-full h-2.5 overflow-hidden">
<div class="bg-red-500 h-2.5 rounded-full" style="width: 8%"></div>
</div>
</div>
<div>
<div class="flex justify-between text-sm mb-1.5">
<span class="font-medium text-gray-700 flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-gray-500"></span> Rusak / Hilang
                        </span>
<span class="font-bold text-gray-900">5% (62)</span>
</div>
<div class="w-full bg-gray-50 rounded-full h-2.5 overflow-hidden">
<div class="bg-gray-500 h-2.5 rounded-full" style="width: 5%"></div>
</div>
</div>
</div>
</div>
<div class="flex items-center justify-center p-4 md:border-l md:border-gray-100 md:pl-10">
<div class="relative size-60 flex-shrink-0">
<div class="w-full h-full rounded-full transition-all hover:scale-105 duration-300" style="background: conic-gradient(#22c55e 0% 75%, #facc15 75% 87%, #ef4444 87% 95%, #6b7280 95% 100%); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);"></div>
<div class="absolute inset-4 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
<span class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Total</span>
<span class="text-4xl font-black text-gray-900 tracking-tight">1,240</span>
<span class="text-gray-500 text-sm font-medium mt-1">Unit Aset</span>
</div>
</div>
</div>
</div>
</div>
<h2 class="text-gray-900 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-8">Daftar Permohonan Perbaikan Aset</h2>
<div class="px-4 py-3 @container">
<div class="flex overflow-hidden rounded-xl bg-white shadow-sm border border-gray-100">
<table class="flex-1 w-full">
<thead class="bg-gray-50/50">
<tr class="border-b border-b-gray-200">
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[15%]">ID Laporan</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[35%]">Nama Aset</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[20%]">Pelapor</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[20%]">Status</th>
<th class="px-6 py-4 text-right text-gray-500 text-xs font-semibold uppercase tracking-wider w-[10%]">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
<tr class="hover:bg-gray-50 transition-colors">
<td class="h-[72px] px-6 py-2 text-gray-500 text-sm font-medium leading-normal">DMG-001</td>
<td class="h-[72px] px-6 py-2">
<div class="flex flex-col justify-center h-full">
<span class="text-gray-900 text-sm font-medium">Monitor Samsung 27"</span>
<span class="text-gray-400 text-xs">Elektronik</span>
</div>
</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">John Doe</td>
<td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
<span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-2"></span>In Progress
                                        </span>
</td>
<td class="h-[72px] px-6 py-2 text-right">
<button class="text-primary hover:text-blue-700 text-sm font-semibold cursor-pointer">Lihat Detail</button>
</td>
</tr>
<tr class="hover:bg-gray-50 transition-colors">
<td class="h-[72px] px-6 py-2 text-gray-500 text-sm font-medium leading-normal">DMG-002</td>
<td class="h-[72px] px-6 py-2">
<div class="flex flex-col justify-center h-full">
<span class="text-gray-900 text-sm font-medium">Meja Kerja Staff</span>
<span class="text-gray-400 text-xs">Furniture</span>
</div>
</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Jane Smith</td>
<td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
<span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2"></span>Resolved
                                        </span>
</td>
<td class="h-[72px] px-6 py-2 text-right">
<button class="text-primary hover:text-blue-700 text-sm font-semibold cursor-pointer">Lihat Detail</button>
</td>
</tr>
<tr class="hover:bg-gray-50 transition-colors">
<td class="h-[72px] px-6 py-2 text-gray-500 text-sm font-medium leading-normal">DMG-003</td>
<td class="h-[72px] px-6 py-2">
<div class="flex flex-col justify-center h-full">
<span class="text-gray-900 text-sm font-medium">Printer HP LaserJet</span>
<span class="text-gray-400 text-xs">Elektronik</span>
</div>
</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Mike Johnson</td>
<td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
<span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></span>New Report
                                        </span>
</td>
<td class="h-[72px] px-6 py-2 text-right">
<button class="text-primary hover:text-blue-700 text-sm font-semibold cursor-pointer">Lihat Detail</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<h2 class="text-gray-900 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-8">Daftar Permohonan Mutasi Aset</h2>
<div class="px-4 py-3 @container">
<div class="flex overflow-hidden rounded-xl bg-white shadow-sm border border-gray-100">
<table class="flex-1 w-full">
<thead class="bg-gray-50/50">
<tr class="border-b border-b-gray-200">
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[15%]">ID Request</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[25%]">Nama Aset</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[20%]">Dari Lokasi</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[20%]">Ke Lokasi</th>
<th class="px-6 py-4 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider w-[10%]">Status</th>
<th class="px-6 py-4 text-right text-gray-500 text-xs font-semibold uppercase tracking-wider w-[10%]">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
<tr class="hover:bg-gray-50 transition-colors">
<td class="h-[72px] px-6 py-2 text-gray-500 text-sm font-medium leading-normal">REQ-001</td>
<td class="h-[72px] px-6 py-2">
<div class="flex flex-col justify-center h-full">
<span class="text-gray-900 text-sm font-medium">Laptop Dell XPS 15</span>
<span class="text-gray-400 text-xs">IT Equipment</span>
</div>
</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Gudang A</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Kantor Pemasaran</td>
<td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
<span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-2"></span>Pending
                                        </span>
</td>
<td class="h-[72px] px-6 py-2 text-right">
<button class="text-primary hover:text-blue-700 text-sm font-semibold cursor-pointer">Lihat Detail</button>
</td>
</tr>
<tr class="hover:bg-gray-50 transition-colors">
<td class="h-[72px] px-6 py-2 text-gray-500 text-sm font-medium leading-normal">REQ-002</td>
<td class="h-[72px] px-6 py-2">
<div class="flex flex-col justify-center h-full">
<span class="text-gray-900 text-sm font-medium">Proyektor Epson</span>
<span class="text-gray-400 text-xs">Multimedia</span>
</div>
</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Ruang Rapat B</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Ruang Rapat C</td>
<td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
<span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2"></span>Approved
                                        </span>
</td>
<td class="h-[72px] px-6 py-2 text-right">
<button class="text-primary hover:text-blue-700 text-sm font-semibold cursor-pointer">Lihat Detail</button>
</td>
</tr>
<tr class="hover:bg-gray-50 transition-colors">
<td class="h-[72px] px-6 py-2 text-gray-500 text-sm font-medium leading-normal">REQ-003</td>
<td class="h-[72px] px-6 py-2">
<div class="flex flex-col justify-center h-full">
<span class="text-gray-900 text-sm font-medium">Kursi Ergonomis</span>
<span class="text-gray-400 text-xs">Furniture</span>
</div>
</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Gudang B</td>
<td class="h-[72px] px-6 py-2 text-gray-600 text-sm font-normal leading-normal">Kantor HRD</td>
<td class="h-[72px] px-6 py-2 text-sm font-normal leading-normal">
<span class="inline-flex items-center justify-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
<span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2"></span>Rejected
                                        </span>
</td>
<td class="h-[72px] px-6 py-2 text-right">
<button class="text-primary hover:text-blue-700 text-sm font-semibold cursor-pointer">Lihat Detail</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>

@endsection
