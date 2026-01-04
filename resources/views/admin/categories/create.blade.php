@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Tambah Lokasi</h2>

    <x-alert />

    <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
        @csrf

        <x-input label="Nama Kategori" name="name" required />

        
        <select name="parent_id" class="form-input w-full rounded-lg">
            <option value="">-- Parent Lokasi --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        
        <textarea name="description" class="form-input w-full rounded-lg" placeholder="Deskripsi"></textarea>

        <select name="status" class="form-input w-full rounded-lg">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
        </select>

        <x-form-actions label="Simpan" cancelLabel="Batal" cancelRoute="{{ route('locations.index') }}" />
    </form>
</div>
@endsection
