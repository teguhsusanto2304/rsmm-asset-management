@extends('layouts.admin.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Tambah Permission</h1>
            <p class="text-[#617989]">Buat permission baru untuk sistem</p>
        </div>
        <a href="{{ route('permissions.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('permissions.store') }}" class="bg-white rounded-xl border border-[#dbe1e6] p-6 space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium mb-2">
                Nama Permission
                <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   placeholder="Contoh: create-asset, edit-user, delete-report"
                   value="{{ old('name') }}"
                   class="w-full form-input rounded-lg @error('name') border-red-500 @enderror"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Gunakan format lowercase dengan dash (contoh: view-dashboard)</p>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-2">
                Deskripsi (Opsional)
            </label>
            <textarea id="description"
                      name="description"
                      rows="3"
                      placeholder="Jelaskan apa yang permission ini lakukan..."
                      class="w-full form-input rounded-lg resize-none">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">save</span>
                Simpan Permission
            </button>
            <a href="{{ route('permissions.index') }}"
               class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                <span class="material-symbols-outlined">cancel</span>
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
