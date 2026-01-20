@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <a href="{{ route('maintenance-schedule.index') }}" class="flex items-center text-primary hover:text-primary/80 mb-6">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Kembali
    </a>

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 sm:p-6">
        <h1 class="text-2xl font-black text-[#111518] mb-6">Edit Jadwal Pemeliharaan</h1>

        <form method="POST" action="{{ route('maintenance-schedule.update', $maintenanceSchedule) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Asset Selection --}}
            <div>
                <label class="block text-sm font-bold mb-2">Pilih Aset <span class="text-red-500">*</span></label>
                <select name="asset_id" required class="w-full form-input rounded-lg @error('asset_id') border-red-500 @enderror">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ $maintenanceSchedule->asset_id == $asset->id ? 'selected' : '' }}>
                            {{ $asset->name }} ({{ $asset->barcode }})
                        </option>
                    @endforeach
                </select>
                @error('asset_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-bold mb-2">Nama Jadwal <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full form-input rounded-lg @error('name') border-red-500 @enderror" 
                       placeholder="contoh: Pemeriksaan Berkala Bulanan" value="{{ $maintenanceSchedule->name }}">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full form-input rounded-lg" placeholder="Deskripsi detail jadwal pemeliharaan...">{{ $maintenanceSchedule->description }}</textarea>
            </div>

            {{-- Maintenance Notes --}}
            <div>
                <label class="block text-sm font-bold mb-2">Catatan Pemeliharaan</label>
                <textarea name="maintenance_notes" rows="3" class="w-full form-input rounded-lg" placeholder="Instruksi khusus untuk teknisi...">{{ $maintenanceSchedule->maintenance_notes }}</textarea>
            </div>

            {{-- Frequency --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Frekuensi <span class="text-red-500">*</span></label>
                    <select name="frequency" required class="w-full form-input rounded-lg @error('frequency') border-red-500 @enderror">
                        <option value="">-- Pilih Frekuensi --</option>
                        @foreach($frequencies as $key => $label)
                            <option value="{{ $key }}" {{ $maintenanceSchedule->frequency == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('frequency')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <label class="block text-sm font-bold mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required class="w-full form-input rounded-lg @error('start_date') border-red-500 @enderror"
                           value="{{ $maintenanceSchedule->start_date->format('Y-m-d') }}">
                    @error('start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Priority and Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Prioritas <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full form-input rounded-lg @error('priority') border-red-500 @enderror">
                        <option value="">-- Pilih Prioritas --</option>
                        @foreach($priorities as $key => $label)
                            <option value="{{ $key }}" {{ $maintenanceSchedule->priority == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full form-input rounded-lg @error('status') border-red-500 @enderror">
                        <option value="active" {{ $maintenanceSchedule->status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="paused" {{ $maintenanceSchedule->status == 'paused' ? 'selected' : '' }}>Ditunda</option>
                        <option value="completed" {{ $maintenanceSchedule->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Estimated Cost and Hours --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Estimasi Biaya (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="estimated_cost" required min="0" step="1000" class="w-full form-input rounded-lg @error('estimated_cost') border-red-500 @enderror"
                           placeholder="100000" value="{{ $maintenanceSchedule->estimated_cost }}">
                    @error('estimated_cost')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Estimasi Jam Kerja <span class="text-red-500">*</span></label>
                    <input type="number" name="estimated_hours" required min="1" max="100" class="w-full form-input rounded-lg @error('estimated_hours') border-red-500 @enderror"
                           placeholder="1" value="{{ $maintenanceSchedule->estimated_hours }}">
                    @error('estimated_hours')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2 pt-4 border-t">
                <a href="{{ route('maintenance-schedule.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-primary/90">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
