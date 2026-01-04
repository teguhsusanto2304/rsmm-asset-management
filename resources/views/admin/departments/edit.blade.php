@extends('layouts.admin.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6 border">
    <h2 class="text-2xl font-bold mb-6">Edit Departemen</h2>

    {{-- 🔴 Validation Error Alert --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-red-600">error</span>
                <h4 class="font-bold text-red-700">Terjadi Kesalahan</h4>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('departments.update', $department->id) }}"
        class="space-y-4"
    >
        @csrf
        @method('PUT')

        {{-- Nama Departemen --}}
        <x-input
            label="Nama Departemen"
            placeholder="Nama Departemen"
            name="name"
            value="{{ old('department', $department->department) }}"
            required
        />

        {{-- Deskripsi --}}
        <textarea
            name="description"
            placeholder="Deskripsi"
            class="form-input w-full rounded-lg border-gray-300"
        >{{ old('description', $department->description) }}</textarea>

        {{-- Parent Departemen --}}
        <select name="parent_id" class="form-input w-full rounded-lg border-gray-300">
            <option value="">-- Parent Departemen --</option>
            @foreach($parents as $parent)
                <option
                    value="{{ $parent->id }}"
                    {{ old('parent_id', $department->parent_id) == $parent->id ? 'selected' : '' }}
                >
                    {{ $parent->department }}
                </option>
            @endforeach
        </select>

        {{-- Kepala Unit --}}
        <select name="head_user_id" class="form-input w-full rounded-lg border-gray-300">
            <option value="">-- Kepala Unit --</option>
            @foreach($users as $user)
                <option
                    value="{{ $user->id }}"
                    {{ old('head_user_id', $department->head_user_id) == $user->id ? 'selected' : '' }}
                >
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        {{-- Action Buttons --}}
        <x-form-actions label="Update" cancelLabel="Batal" cancelRoute="{{ route('departments.index') }}" />
    </form>
</div>
@endsection
