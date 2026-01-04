@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Tambah Departemen</h2>

    <x-alert />

    <form method="POST" action="{{ route('departments.store') }}" class="space-y-4">
        @csrf

        <x-input 
            label="Nama Departemen"
            placeholder="Nama Departemen"
            name="department"
            value="{{ old('name') }}"
            required
        />

        <textarea
            name="description"
            placeholder="Deskripsi"
            class="form-input w-full rounded-lg border-gray-300"
        >{{ old('description') }}</textarea>

        <select name="parent_id" class="form-input w-full rounded-lg border-gray-300">
            <option value="">-- Parent Departemen --</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                    {{ $parent->department }}
                </option>
            @endforeach
        </select>

        <select name="user_id" class="form-input w-full rounded-lg border-gray-300">
            <option value="">-- Kepala Unit --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('head_user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <x-form-actions label="Simpan" cancelLabel="Batal" cancelRoute="{{ route('departments.index') }}" />
    </form>
</div>
@endsection
