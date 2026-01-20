<aside class="flex w-64 flex-col border-r border-gray-200 bg-white p-4 sm:p-6" 
      @toggle-sidebar.window="$dispatch('close-sidebar')"
      x-data="{ sidebarOpen: true }">
    <!-- Logo Section with Close Button -->
    <div class="flex items-center justify-between pb-6 sm:pb-8">
        <div class="flex items-center gap-3 min-w-0">
            <div class="size-6 text-primary flex-shrink-0">
                <svg viewBox="0 0 48 48" fill="currentColor">
                    <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4Z"/>
                </svg>
            </div>
            <h2 class="text-base sm:text-lg font-bold truncate">{{ config('app.name', 'AssetMinds') }}</h2>
        </div>
        <button @click="$dispatch('close-sidebar')" 
                class="lg:hidden p-2 hover:bg-gray-100 rounded-lg flex-shrink-0">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <nav class="flex flex-1 flex-col justify-between overflow-y-auto">
        <!-- MAIN MENU -->
        <div class="flex flex-col gap-2">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
               {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined flex-shrink-0">dashboard</span>
                <span class="truncate">Dashboard</span>
            </a>

            <!-- My Assets -->
            <a href="{{ route('assets.my-assets') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
               {{ request()->routeIs('assets.my-assets') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined flex-shrink-0">backpack</span>
                <span class="truncate">Asset Saya</span>
            </a>

            <!-- Manajemen Asset -->
            <a href="{{ route('assets.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
               {{ request()->routeIs('assets.*') && !request()->routeIs('assets.my-assets') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined flex-shrink-0">inventory_2</span>
                <span class="truncate">Manajemen Asset</span>
            </a>

            <!-- Asset Transfer Requests -->
            <a href="{{ route('asset-transfers.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
               {{ request()->routeIs('asset-transfers.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined flex-shrink-0">compare_arrows</span>
                <span class="truncate">Transfer Asset</span>
            </a>

            <!-- Maintenance Management -->
            <a href="{{ url('/master-data/maintenance') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
               {{ request()->routeIs('maintenance.*') || request()->is('master-data/maintenance*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined flex-shrink-0">build</span>
                <span class="truncate">Pemeliharaan</span>
            </a>

            <!-- Maintenance Schedule -->
            <a href="{{ route('maintenance-schedule.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
               {{ request()->routeIs('maintenance-schedule.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined flex-shrink-0">event_repeat</span>
                <span class="truncate">Jadwal Pemeliharaan</span>
            </a>

            <!-- Technician Area (Show only for technicians) -->
            @if(auth()->user()->hasRole('technician'))
                <div x-data="{ techOpen: {{ request()->routeIs('technician.*') ? 'true' : 'false' }} }" class="flex flex-col">
                    <!-- Parent -->
                    <button
                        @click="techOpen = !techOpen"
                        type="button"
                        class="flex items-center justify-between gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
                        {{ request()->routeIs('technician.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="material-symbols-outlined flex-shrink-0">handyman</span>
                            <span class="truncate">Area Teknisi</span>
                        </div>
                        <span class="material-symbols-outlined text-base flex-shrink-0"
                              x-text="techOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>

                    <!-- Sub Menu -->
                    <div x-show="techOpen" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                        <a href="{{ url('/master-data/technician') }}"
                           class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                           {{ request()->routeIs('technician.dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined text-base flex-shrink-0">dashboard</span>
                            <span class="truncate">Dashboard</span>
                        </a>
                        <a href="{{ url('/master-data/technician/maintenance') }}"
                           class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                           {{ request()->routeIs('technician.maintenance') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined text-base flex-shrink-0">task_alt</span>
                            <span class="truncate">Pekerjaan Saya</span>
                        </a>
                        <a href="{{ url('/master-data/technician/statistics') }}"
                           class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                           {{ request()->routeIs('technician.statistics') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined text-base flex-shrink-0">trending_up</span>
                            <span class="truncate">Statistik</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- MASTER DATA -->
            <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('departments.*') || request()->routeIs('locations.*') || request()->routeIs('categories.*') || request()->routeIs('assets.*') || request()->routeIs('permissions.*') || request()->routeIs('roles.*') ? 'true' : 'false' }} }" class="flex flex-col">
                <!-- Parent -->
                <button
                    @click="open = !open"
                    type="button"
                    class="flex items-center justify-between gap-3 rounded-lg px-4 py-2 text-sm font-medium transition
                    text-gray-600 hover:bg-gray-100"
                >
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="material-symbols-outlined flex-shrink-0">database</span>
                        <span class="truncate">Master Data</span>
                    </div>
                    <span class="material-symbols-outlined text-base flex-shrink-0"
                          x-text="open ? 'expand_less' : 'expand_more'"></span>
                </button>

                <!-- Sub Menu -->
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('users.index') }}"
                       class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                       {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined text-base flex-shrink-0">person</span>
                        <span class="truncate">Manajemen User</span>
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('roles.index') }}"
                       class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                       {{ request()->routeIs('roles.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined text-base flex-shrink-0">security</span>
                        <span class="truncate">Manajemen Role</span>
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('permissions.index') }}"
                       class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                       {{ request()->routeIs('permissions.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined text-base flex-shrink-0">admin_panel_settings</span>
                        <span class="truncate">Manajemen Permission</span>
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('departments.index') }}"
                       class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                       {{ request()->routeIs('departments.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined text-base flex-shrink-0">apartment</span>
                        <span class="truncate">Departemen</span>
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('locations.index') }}"
                       class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                       {{ request()->routeIs('locations.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined text-base flex-shrink-0">location_on</span>
                        <span class="truncate">Lokasi</span>
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('categories.index') }}"
                       class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                       {{ request()->routeIs('categories.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined text-base flex-shrink-0">category</span>
                        <span class="truncate">Kategori</span>
                    </a>
                </div>
                
            </div>

        </div>

        <!-- BOTTOM SECTION -->
        <div class="flex flex-col gap-4 border-t pt-4 mt-4">
            @php $user = auth()->user(); @endphp

            <!-- User Info -->
            <div class="flex items-center gap-3 min-w-0 px-2">
                <div class="rounded-full size-10 bg-gray-200 flex-shrink-0"></div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                </div>
            </div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-bold text-white hover:bg-red-600 transition"
                >
                    <span class="material-symbols-outlined text-base">logout</span>
                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.listen('close-sidebar', () => {
                // Close sidebar by dispatching custom event up to parent
                document.body.dispatchEvent(new CustomEvent('close-mobile-sidebar'));
            });
        });

        // Close sidebar when clicking links on mobile
        document.querySelectorAll('[href]').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    document.body.dispatchEvent(new CustomEvent('close-mobile-sidebar'));
                }
            });
        });
    </script>
</aside>
