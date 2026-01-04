@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Tambah Lokasi</h2>

    <x-alert />

    <form method="POST" action="{{ route('locations.store') }}" class="space-y-4">
        @csrf

        <x-input label="Nama Lokasi" name="name" required />

        <select name="type" class="form-input w-full rounded-lg">
            <option value="">-- Tipe Lokasi --</option>
            <option value="gedung">Gedung</option>
            <option value="lantai">Lantai</option>
            <option value="ruang">Ruang</option>
        </select>

        <select name="parent_id" class="form-input w-full rounded-lg">
            <option value="">-- Parent Lokasi --</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}">
                    {{ ucfirst($location->type) }} - {{ $location->name }}
                </option>
            @endforeach
        </select>

        <select name="department_id" class="form-input w-full rounded-lg">
            <option value="">-- Departemen (Opsional) --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->department }}</option>
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
