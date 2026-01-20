@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#111518]">Lapor Pemeliharaan Aset</h1>
            <p class="text-sm sm:text-base text-[#617989]">Buat laporan untuk aset yang perlu perbaikan</p>
        </div>
        <a href="{{ route('maintenance.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <form method="POST" action="{{ url('/master-data/maintenance') }}" class="space-y-6">
        @csrf

        {{-- Asset Selection --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6 space-y-5">
            <h3 class="text-lg font-semibold">Pilih Aset</h3>

            <div>
                <label for="asset_id" class="block text-sm font-medium mb-2">
                    Aset Anda
                    <span class="text-red-500">*</span>
                </label>
                <select id="asset_id"
                        name="asset_id"
                        class="w-full form-input rounded-lg @error('asset_id') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Aset --</option>
                    @foreach($myAssets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->name }} - {{ $asset->category->name }} ({{ $asset->barcode }})
                        </option>
                    @endforeach
                </select>
                @error('asset_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($myAssets->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">Anda belum memiliki aset yang ditugaskan. Hubungi administrator untuk mendapatkan aset.</p>
                </div>
            @endif
        </div>

        {{-- Maintenance Details --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6 space-y-5">
            <h3 class="text-lg font-semibold">Detail Laporan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium mb-2">
                        Jenis Pemeliharaan
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="type"
                            name="type"
                            class="w-full form-input rounded-lg @error('type') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>
                            Perbaikan (Aset Rusak)
                        </option>
                        <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>
                            Pencegahan (Perawatan Rutin)
                        </option>
                        <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>
                            Darurat (Berhenti Total)
                        </option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium mb-2">
                        Prioritas
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="priority"
                            name="priority"
                            class="w-full form-input rounded-lg @error('priority') border-red-500 @enderror"
                            required>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Kritis</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                          placeholder="Jelaskan masalah yang terjadi pada aset..."
                          required>{{ old('issue_description') }}</textarea>
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
                          class="w-full form-input rounded-lg @error('symptoms') border-red-500 @enderror"
                          placeholder="Gejala atau tanda-tanda yang diamati...">{{ old('symptoms') }}</textarea>
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
                       value="{{ old('estimated_hours') }}"
                       class="w-full form-input rounded-lg @error('estimated_hours') border-red-500 @enderror"
                       placeholder="Contoh: 2">
                @error('estimated_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ url('/master-data/maintenance') }}"
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>
@endsection
