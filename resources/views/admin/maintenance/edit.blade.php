@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Edit Laporan Pemeliharaan</h1>
            <p class="text-sm sm:text-base text-[#617989]">{{ $maintenance->reference_number }}</p>
        </div>
        <a href="{{ url('/master-data/maintenance/' . $maintenance->id) }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <form method="POST" action="{{ url('/master-data/maintenance/' . $maintenance->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Maintenance Details --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6 space-y-5">
            <h3 class="text-lg font-semibold">Detail Laporan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="priority" class="block text-sm font-medium mb-2">
                        Prioritas
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="priority"
                            name="priority"
                            class="w-full form-input rounded-lg @error('priority') border-red-500 @enderror"
                            required>
                        <option value="low" {{ old('priority', $maintenance->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ old('priority', $maintenance->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ old('priority', $maintenance->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="critical" {{ old('priority', $maintenance->priority) == 'critical' ? 'selected' : '' }}>Kritis</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Jenis Pemeliharaan</label>
                    <input type="text" value="{{ $maintenance->getTypeLabel() }}" disabled
                           class="w-full form-input rounded-lg bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">Tidak dapat diubah</p>
                </div>
            </div>

            <div>
                <label for="issue_description" class="block text-sm font-medium mb-2">
                    Deskripsi Masalah
                    <span class="text-red-500">*</span>
                </label>
                <textarea id="issue_description"
                          name="issue_description"
                          rows="4"
                          class="w-full form-input rounded-lg @error('issue_description') border-red-500 @enderror"
                          required>{{ old('issue_description', $maintenance->issue_description) }}</textarea>
                @error('issue_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="symptoms" class="block text-sm font-medium mb-2">
                    Gejala / Tanda-tanda
                </label>
                <textarea id="symptoms"
                          name="symptoms"
                          rows="3"
                          class="w-full form-input rounded-lg @error('symptoms') border-red-500 @enderror">{{ old('symptoms', $maintenance->symptoms) }}</textarea>
                @error('symptoms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estimated_hours" class="block text-sm font-medium mb-2">
                    Estimasi Waktu Perbaikan (Jam)
                </label>
                <input type="number"
                       id="estimated_hours"
                       name="estimated_hours"
                       min="1"
                       max="100"
                       value="{{ old('estimated_hours', $maintenance->estimated_hours) }}"
                       class="w-full form-input rounded-lg @error('estimated_hours') border-red-500 @enderror">
                @error('estimated_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Asset Information (Read-only) --}}
        <div class="bg-gray-50 rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
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
                <div class="flex justify-between">
                    <span class="text-gray-600">Kategori</span>
                    <span class="font-semibold">{{ $maintenance->asset->category->name }}</span>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ url('/master-data/maintenance/' . $maintenance->id) }}"
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
