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
            <a href="{{ route('profile.show') }}" class="px-4 py-3 font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent">
                <span class="material-symbols-outlined align-middle mr-2">person</span>
                Profil
            </a>
            <a href="{{ route('profile.change-password') }}" class="px-4 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">
                <span class="material-symbols-outlined align-middle mr-2">lock</span>
                Ubah Password
            </a>
        </nav>
    </div>

    {{-- Change Password Card --}}
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <div class="mb-8">
            <h2 class="text-2xl font-bold">Ubah Password</h2>
            <p class="text-gray-600 mt-2">Pastikan password Anda aman dan tidak dapat ditebak. Gunakan kombinasi huruf besar, kecil, angka, dan simbol.</p>
        </div>

        <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-6 max-w-md">
            @csrf
            @method('PUT')

            {{-- Current Password --}}
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Saat Ini
                </label>
                <div class="relative">
                    <input 
                        type="password"
                        id="current_password"
                        name="current_password"
                        placeholder="Masukkan password saat ini"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('current_password') border-red-500 @enderror"
                    >
                    <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600" onclick="togglePassword('current_password')">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>
                <div class="relative">
                    <input 
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password baru"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                    >
                    <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600" onclick="togglePassword('password')">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

                {{-- Password Requirements --}}
                <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-semibold text-blue-900 mb-2">Persyaratan Password:</p>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            Minimal 8 karakter
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            Mengandung huruf besar (A-Z)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            Mengandung huruf kecil (a-z)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            Mengandung angka (0-9)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                            Mengandung simbol (!@#$%^&*)
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <input 
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Konfirmasi password baru"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('password_confirmation') border-red-500 @enderror"
                    >
                    <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600" onclick="togglePassword('password_confirmation')">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white hover:bg-blue-700 transition">
                    <span class="material-symbols-outlined" style="font-size: 20px;">check</span>
                    Ubah Password
                </button>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-300 transition">
                    <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
}
</script>
@endsection
