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
        <!-- MAIN MENU - Dynamically generated from config -->
        <div class="flex flex-col gap-2">
            @php
                $menuService = app(\App\Services\MenuService::class);
                $menus = $menuService->getAccessibleMenus(auth()->user());
            @endphp

            @foreach($menus as $menu)
                @if(isset($menu['submenu']) && !empty($menu['submenu']))
                    <!-- Submenu Item -->
                    <div x-data="{ open: {{ in_array(request()->route()?->getName(), collect($menu['submenu'])->pluck('route')->toArray()) ? 'true' : 'false' }} }" class="flex flex-col">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex items-center justify-between gap-3 rounded-lg px-4 py-2 text-sm font-medium transition
                            {{ in_array(request()->route()?->getName(), collect($menu['submenu'])->pluck('route')->toArray()) ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="material-symbols-outlined flex-shrink-0">{{ $menu['icon'] }}</span>
                                <span class="truncate">{{ $menu['label'] }}</span>
                            </div>
                            <span class="material-symbols-outlined text-base flex-shrink-0"
                                  x-text="open ? 'expand_less' : 'expand_more'"></span>
                        </button>

                        <!-- Sub Menu Items -->
                        <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                            @foreach($menu['submenu'] as $submenu)
                                <a href="{{ route($submenu['route']) }}"
                                   class="rounded-md px-3 py-2 text-sm flex items-center gap-2 transition truncate
                                   {{ request()->routeIs($submenu['route']) ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <span class="material-symbols-outlined text-base flex-shrink-0">{{ $submenu['icon'] }}</span>
                                    <span class="truncate">{{ $submenu['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Simple Menu Item -->
                    <a href="{{ route($menu['route']) }}"
                       class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold transition
                       {{ request()->routeIs($menu['route']) || (isset($menu['route_pattern']) && request()->routeIs($menu['route_pattern'])) ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span class="material-symbols-outlined flex-shrink-0">{{ $menu['icon'] }}</span>
                        <span class="truncate">{{ $menu['label'] }}</span>
                    </a>
                @endif
            @endforeach

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
