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
        
        <!-- User Menu Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="p-2 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                @if(auth()->user()->avatar)
                    <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                @else
                    <span class="material-symbols-outlined">account_circle</span>
                @endif
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.outside="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden z-50">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                
                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="material-symbols-outlined" style="font-size: 18px;">person</span>
                    <span>Profil</span>
                </a>
                
                <a href="{{ route('profile.change-password') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                    <span class="material-symbols-outlined" style="font-size: 18px;">lock</span>
                    <span>Ubah Password</span>
                </a>
                
                <div class="border-t border-gray-200"></div>
                
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-700 hover:bg-red-50 transition">
                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
