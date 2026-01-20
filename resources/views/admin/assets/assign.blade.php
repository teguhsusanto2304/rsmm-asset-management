@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Tugaskan Asset ke Pengguna</h1>
            <p class="text-[#617989]">Tetapkan pengguna yang akan menggunakan asset ini.</p>
        </div>
        <a href="{{ route('assets.show', $asset) }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Asset Information --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('assets.assign.process', $asset) }}" class="space-y-6">
                @csrf

                {{-- Asset Details Card --}}
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Asset</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Nama Asset</span>
                            <span class="font-semibold">{{ $asset->name }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Barcode</span>
                            <span class="font-mono text-sm">{{ $asset->barcode }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Serial Number</span>
                            <span class="font-mono text-sm">{{ $asset->serial_number }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Kategori</span>
                            <span class="font-semibold">{{ $asset->category->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                {{ $asset->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($asset->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Assignment Form --}}
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-6 space-y-5">
                    <h3 class="text-lg font-semibold">Informasi Penugasan</h3>

                    {{-- User Selection --}}
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium mb-2">
                            Pengguna
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="assigned_to"
                                name="assigned_to"
                                class="w-full form-input rounded-lg @error('assigned_to') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ old('assigned_to', $asset->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Assignment Date --}}
                    <div>
                        <label for="assigned_date" class="block text-sm font-medium mb-2">
                            Tanggal Penugasan
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="assigned_date"
                               name="assigned_date"
                               value="{{ old('assigned_date', $asset->assigned_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="w-full form-input rounded-lg @error('assigned_date') border-red-500 @enderror"
                               required>
                        @error('assigned_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Return Date --}}
                    <div>
                        <label for="return_date" class="block text-sm font-medium mb-2">
                            Tanggal Pengembalian (Opsional)
                        </label>
                        <input type="date"
                               id="return_date"
                               name="return_date"
                               value="{{ old('return_date', $asset->return_date?->format('Y-m-d')) }}"
                               class="w-full form-input rounded-lg @error('return_date') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika belum tahu tanggal pengembalian</p>
                        @error('return_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  placeholder="Tambahkan catatan tentang penugasan ini..."
                                  class="w-full form-input rounded-lg resize-none @error('notes') border-red-500 @enderror"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                        <span class="material-symbols-outlined">check_circle</span>
                        Tugaskan Asset
                    </button>
                    <a href="{{ route('assets.show', $asset) }}"
                       class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                        <span class="material-symbols-outlined">cancel</span>
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Current Assignment Info Sidebar --}}
        <div class="lg:col-span-1">
            @if($asset->assignedUser)
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-4">
                    <h4 class="font-semibold text-blue-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">info</span>
                        Penugasan Saat Ini
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-blue-700 font-semibold mb-1">PENGGUNA</p>
                            <p class="text-sm font-semibold">{{ $asset->assignedUser->name }}</p>
                        </div>
                        @if($asset->assigned_date)
                            <div>
                                <p class="text-xs text-blue-700 font-semibold mb-1">TANGGAL PENUGASAN</p>
                                <p class="text-sm">{{ $asset->assigned_date->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if($asset->return_date)
                            <div>
                                <p class="text-xs text-blue-700 font-semibold mb-1">TANGGAL PENGEMBALIAN</p>
                                <p class="text-sm">{{ $asset->return_date->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">info</span>
                        Informasi
                    </h4>
                    <p class="text-sm text-gray-600">Asset ini belum ditugaskan kepada siapa pun. Isi formulir di sebelah untuk menugaskannya.</p>
                </div>
            @endif

            {{-- Quick Links --}}
            <div class="mt-6 space-y-2">
                <a href="{{ route('assets.show', $asset) }}"
                   class="block text-center px-4 py-2 text-sm font-semibold text-primary hover:text-primary/90 border border-primary rounded-lg hover:bg-primary/5">
                    Lihat Detail Asset
                </a>
                <a href="{{ route('assets.edit', $asset) }}"
                   class="block text-center px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 border border-gray-200 rounded-lg hover:bg-gray-50">
                    Edit Asset
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
