<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Halaman Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#1392ec",
                        "background-light": "#f6f7f8",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light font-display text-gray-800">
<div class="flex min-h-screen w-full items-center justify-center">
    <div class="flex w-full max-w-6xl overflow-hidden rounded-xl shadow-2xl bg-white m-4">
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-end p-12 bg-cover bg-center text-white" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDJo46Pije0fekvkY7CXttMueuxLz_2EGicuROva1k6941IC8ZUXI45hE-KdVA2bCK195dsmSs3qaPmXHt2k0hQIdNRzuhAUX00QAwPk9RQo4qpocg-RLgaBsnrCs0PA3SLYPC6x1UE_BG_1B_li3RogarDyf_0Kl5lM2_FpB7KouTRu9-7PkD7yW3VItm6HPOQQFqnElqRJb9-11nmfCGt-dGSKsJqp-Rj01jecvoFdr4BWPwktpuFmIeEQUQ9YsQWMMo5qE1l6xSM');">
            <div class="bg-black/40 p-6 rounded-lg backdrop-blur-sm">
                <h1 class="text-4xl font-bold leading-tight">Manajemen Aset Menjadi Mudah</h1>
                <p class="mt-2 text-lg font-light">Lacak, kelola, dan optimalkan semua aset perusahaan Anda dari satu dasbor terpusat.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
            <div class="w-full max-w-md mx-auto">
                <div class="mb-8 text-center lg:text-left">
                    <div class="flex items-center gap-2 justify-center lg:justify-start">
                        <span class="material-symbols-outlined text-primary text-3xl">inventory</span>
                        <h2 class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</h2>
                    </div>
                </div>

                <h3 class="text-3xl font-bold text-gray-900">Selamat Datang Kembali</h3>
                <p class="mt-2 text-gray-600">Masukkan kredensial Anda untuk mengakses dashboard.</p>

                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 text-sm font-medium">Login Gagal</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 flex flex-col gap-6">
                    @csrf

                    <label class="flex flex-col w-full">
                        <p class="text-sm font-medium leading-normal pb-2 text-gray-700">Username atau Email</p>
                        <div class="flex w-full flex-1 items-stretch rounded-lg">
                            <div class="text-gray-400 flex border border-gray-300 bg-gray-50 items-center justify-center pl-4 rounded-l-lg border-r-0">
                                <span class="material-symbols-outlined" style="font-size: 20px;">person</span>
                            </div>
                            <input 
                                name="email" 
                                type="email" 
                                placeholder="email@example.com" 
                                value="{{ old('email') }}"
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-gray-900 focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 bg-white focus:border-primary h-12 placeholder:text-gray-400 p-3 rounded-r-lg border-l-0 text-base font-normal leading-normal @error('email') border-red-500 @enderror"
                                required
                            />
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="flex flex-col w-full">
                        <p class="text-sm font-medium leading-normal pb-2 text-gray-700">Password</p>
                        <div class="flex w-full flex-1 items-stretch rounded-lg">
                            <div class="text-gray-400 flex border border-gray-300 bg-gray-50 items-center justify-center pl-4 rounded-l-lg border-r-0">
                                <span class="material-symbols-outlined" style="font-size: 20px;">lock</span>
                            </div>
                            <input 
                                name="password" 
                                type="password" 
                                placeholder="Masukkan password Anda"
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-gray-900 focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 bg-white focus:border-primary h-12 placeholder:text-gray-400 p-3 rounded-r-lg border-l-0 text-base font-normal leading-normal @error('password') border-red-500 @enderror"
                                required
                            />
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <div class="text-right">
                        <a class="text-primary text-sm font-medium leading-normal hover:underline" href="{{ route('password.request') }}">Lupa Password?</a>
                    </div>

                    <button type="submit" class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 transition-colors duration-200">
                        <span class="truncate">Masuk</span>
                    </button>
                </form>

                <div class="mt-12">
                    <footer class="text-center">
                        <p class="text-sm text-gray-500">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>