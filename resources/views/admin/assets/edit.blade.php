@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Edit Asset</h2>

    <x-alert />

    <form method="POST" action="{{ route('assets.update', $asset) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Nama Asset" name="name" :value="old('name', $asset->name)" required />
            <x-input label="Barcode" name="barcode" :value="old('barcode', $asset->barcode)" />
            <x-input label="Asset Tag" name="asset_tag" :value="old('asset_tag', $asset->asset_tag)" />
            <x-input label="Serial Number" name="serial_number" :value="old('serial_number', $asset->serial_number)" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id" class="form-input w-full rounded-lg" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $asset->category_id) == $category->id)>
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
                        <option value="{{ $dept->id }}" @selected(old('department_id', $asset->department_id) == $dept->id)>
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
                        <option value="{{ $loc->id }}" @selected(old('location_id', $asset->location_id) == $loc->id)>
                            {{ $loc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-input label="Merk / Manufacturer" name="manufacturer" :value="old('manufacturer', $asset->manufacturer)" />
            <x-input label="Model" name="model" :value="old('model', $asset->model)" />
            <x-input label="Model Number" name="model_number" :value="old('model_number', $asset->model_number)" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="form-input w-full rounded-lg" required>
                    @foreach(['available','assigned','maintenance','disposed','reserved'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $asset->status) === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kondisi</label>
                <select name="condition" class="form-input w-full rounded-lg" required>
                    @foreach(['excellent','good','fair','poor','critical'] as $condition)
                        <option value="{{ $condition }}" @selected(old('condition', $asset->condition) === $condition)>
                            {{ ucfirst($condition) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" class="form-input w-full rounded-lg" rows="3">{{ old('description', $asset->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-input type="date" label="Tanggal Pembelian" name="purchase_date" :value="old('purchase_date', optional($asset->purchase_date)->format('Y-m-d'))" />
            <x-input type="number" step="0.01" label="Harga Beli" name="purchase_price" :value="old('purchase_price', $asset->purchase_price)" />
            <x-input type="number" step="0.01" label="Nilai Saat Ini" name="current_value" :value="old('current_value', $asset->current_value)" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Pengguna (Assigned To)</label>
                <select name="assigned_to" class="form-input w-full rounded-lg">
                    <option value="">-- Tidak ada --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_to', $asset->assigned_to) == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <x-input type="date" label="Tanggal Penugasan" name="assigned_date" :value="old('assigned_date', optional($asset->assigned_date)->format('Y-m-d'))" />
        </div>

        <x-form-actions label="Update" cancelLabel="Batal" cancelRoute="{{ route('assets.index') }}" />
    </form>
</div>
@endsection


