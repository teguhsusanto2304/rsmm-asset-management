@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Edit Lokasi</h2>

    <x-alert />

    <form method="POST" action="{{ route('locations.update', $location->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Nama Lokasi --}}
        <x-input
            label="Nama Lokasi"
            name="name"
            value="{{ old('name', $location->name) }}"
            required
        />

        {{-- Tipe Lokasi --}}
        <select name="type" class="form-input w-full rounded-lg">
            <option value="">-- Tipe Lokasi --</option>
            <option value="gedung" {{ old('type', $location->type) == 'gedung' ? 'selected' : '' }}>Gedung</option>
            <option value="lantai" {{ old('type', $location->type) == 'lantai' ? 'selected' : '' }}>Lantai</option>
            <option value="ruang" {{ old('type', $location->type) == 'ruang' ? 'selected' : '' }}>Ruang</option>
        </select>

        {{-- Parent Lokasi --}}
        <select name="parent_id" class="form-input w-full rounded-lg">
            <option value="">-- Parent Lokasi --</option>
            @foreach($locations as $parent)
                @if($parent->id !== $location->id)
                    <option value="{{ $parent->id }}"
                        {{ old('parent_id', $location->parent_id) == $parent->id ? 'selected' : '' }}>
                        {{ ucfirst($parent->type) }} - {{ $parent->name }}
                    </option>
                @endif
            @endforeach
        </select>

        {{-- Departemen --}}
        <select name="department_id" class="form-input w-full rounded-lg">
            <option value="">-- Departemen (Opsional) --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ old('department_id', $location->department_id) == $dept->id ? 'selected' : '' }}>
                    {{ $dept->department }}
                </option>
            @endforeach
        </select>

        {{-- Deskripsi --}}
        <textarea
            name="description"
            class="form-input w-full rounded-lg"
            placeholder="Deskripsi"
        >{{ old('description', $location->description) }}</textarea>

        {{-- Status --}}
        <select name="status" class="form-input w-full rounded-lg">
            <option value="aktif" {{ old('status', $location->status) == 'aktif' ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="nonaktif" {{ old('status', $location->status) == 'nonaktif' ? 'selected' : '' }}>
                Nonaktif
            </option>
        </select>

        {{-- Action --}}
        
        <x-form-actions label="Update" cancelLabel="Batal" cancelRoute="{{ route('locations.index') }}" />
    </form>
</div>
@endsection
