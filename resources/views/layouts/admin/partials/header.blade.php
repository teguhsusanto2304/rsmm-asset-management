<header class="bg-white border-b px-4 sm:px-6 lg:px-10 py-3 sm:py-4 flex items-center justify-between sticky top-0 z-30">
    <div class="flex items-center gap-4 flex-1 min-w-0">
        <button @click="$dispatch('toggle-sidebar')" 
                class="lg:hidden p-2 hover:bg-gray-100 rounded-lg flex-shrink-0">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="text-lg sm:text-2xl font-bold text-gray-800 truncate">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>
    <div class="hidden sm:flex items-center gap-2 sm:gap-4 flex-shrink-0">
        <button class="p-2 hover:bg-gray-100 rounded-lg">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="hidden md:block w-px h-6 bg-gray-200"></div>
        <button class="p-2 hover:bg-gray-100 rounded-lg">
            <span class="material-symbols-outlined">account_circle</span>
        </button>
    </div>
</header>
