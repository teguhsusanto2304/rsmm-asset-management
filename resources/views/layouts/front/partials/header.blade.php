<header class="sticky top-0 z-50 w-full bg-white/90 dark:bg-background-dark/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-[1280px] mx-auto px-4 md:px-10 h-16 md:h-20 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl md:text-4xl">inventory_2</span>
            </div>
            <h2 class="text-lg md:text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ config('app.name') }}</h2>
        </div>
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-300 dark:hover:text-white transition-colors" href="#features">Fitur</a>

            <a class="text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-300 dark:hover:text-white transition-colors" href="#about">Tentang Kami</a>
            <a class="text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-300 dark:hover:text-white transition-colors" href="#contact">Kontak</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden md:flex h-10 px-4 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Masuk</a>
                
            @endauth
        </div>
    </div>
</header>