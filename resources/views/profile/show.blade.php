@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

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

    {{-- Tabs Navigation --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex gap-8">
            <a href="{{ route('profile.show') }}" class="px-4 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">
                <span class="material-symbols-outlined align-middle mr-2">person</span>
                Profil
            </a>
            <a href="{{ route('profile.change-password') }}" class="px-4 py-3 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent">
                <span class="material-symbols-outlined align-middle mr-2">lock</span>
                Ubah Password
            </a>
        </nav>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <h2 class="text-2xl font-bold mb-6">Informasi Profil</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Avatar Section --}}
            <div class="flex gap-6 items-start">
                <div>
                    @if ($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center border-4 border-gray-200">
                            <span class="material-symbols-outlined text-blue-600" style="font-size: 80px;">account_circle</span>
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <input 
                        type="file" 
                        name="avatar" 
                        accept="image/*"
                        class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            cursor-pointer"
                    >
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF maksimal 2MB</p>
                </div>
            </div>

            {{-- Name Field --}}
            <x-input 
                label="Nama Lengkap"
                placeholder="Masukkan nama lengkap"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
            />

            {{-- Email Field --}}
            <x-input 
                label="Email"
                type="email"
                placeholder="Masukkan email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
            />

            {{-- Department Field (Read-only) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                <input 
                    type="text" 
                    value="{{ $user->department?->department ?? 'Tidak ada departemen' }}"
                    disabled
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500"
                >
            </div>

            {{-- Role Field (Read-only) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Peran</label>
                <input 
                    type="text" 
                    value="{{ $user->roles->pluck('name')->join(', ') ?? 'Tidak ada peran' }}"
                    disabled
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500"
                >
            </div>

            {{-- Status Field (Read-only) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center gap-2">
                    @if ($user->status === 'active')
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-sm font-semibold text-green-700">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-sm font-semibold text-red-700">
                            <span class="material-symbols-outlined" style="font-size: 16px;">block</span>
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            {{-- Timestamps (Read-only) --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat Pada</label>
                    <input 
                        type="text" 
                        value="{{ $user->created_at->translatedFormat('d F Y H:i') }}"
                        disabled
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diperbarui Pada</label>
                    <input 
                        type="text" 
                        value="{{ $user->updated_at->translatedFormat('d F Y H:i') }}"
                        disabled
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500"
                    >
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white hover:bg-blue-700 transition">
                    <span class="material-symbols-outlined" style="font-size: 20px;">check</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-300 transition">
                    <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
