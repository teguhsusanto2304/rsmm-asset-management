@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Tambah Asset</h2>

    <x-alert />

    <form method="POST" action="{{ route('assets.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Nama Asset" name="name" :value="old('name')" required />
            <x-input label="Barcode (opsional, akan dibuat otomatis jika kosong)" name="barcode" :value="old('barcode')" />
            <x-input label="Asset Tag (opsional)" name="asset_tag" :value="old('asset_tag')" />
            <x-input label="Serial Number" name="serial_number" :value="old('serial_number')" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id" class="form-input w-full rounded-lg" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Departemen</label>
                <select name="department_id" class="form-input w-full rounded-lg">
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Lokasi</label>
                <select name="location_id" class="form-input w-full rounded-lg">
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" @selected(old('location_id') == $loc->id)>
                            {{ $loc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-input label="Merk / Manufacturer" name="manufacturer" :value="old('manufacturer')" />
            <x-input label="Model" name="model" :value="old('model')" />
            <x-input label="Model Number" name="model_number" :value="old('model_number')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="form-input w-full rounded-lg" required>
                    <option value="available" @selected(old('status') === 'available')>Available</option>
                    <option value="assigned" @selected(old('status') === 'assigned')>Assigned</option>
                    <option value="maintenance" @selected(old('status') === 'maintenance')>Maintenance</option>
                    <option value="disposed" @selected(old('status') === 'disposed')>Disposed</option>
                    <option value="reserved" @selected(old('status') === 'reserved')>Reserved</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kondisi</label>
                <select name="condition" class="form-input w-full rounded-lg" required>
                    <option value="excellent" @selected(old('condition') === 'excellent')>Excellent</option>
                    <option value="good" @selected(old('condition', 'good') === 'good')>Good</option>
                    <option value="fair" @selected(old('condition') === 'fair')>Fair</option>
                    <option value="poor" @selected(old('condition') === 'poor')>Poor</option>
                    <option value="critical" @selected(old('condition') === 'critical')>Critical</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" class="form-input w-full rounded-lg" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-input type="date" label="Tanggal Pembelian" name="purchase_date" :value="old('purchase_date')" />
            <x-input type="number" step="0.01" label="Harga Beli" name="purchase_price" :value="old('purchase_price')" />
            <x-input type="number" step="0.01" label="Nilai Saat Ini" name="current_value" :value="old('current_value')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Pengguna (Assigned To)</label>
                <select name="assigned_to" class="form-input w-full rounded-lg">
                    <option value="">-- Tidak ada --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <x-input type="date" label="Tanggal Penugasan" name="assigned_date" :value="old('assigned_date')" />
        </div>

        <x-form-actions label="Simpan" cancelLabel="Batal" cancelRoute="{{ route('assets.index') }}" />
    </form>
</div>
@endsection


