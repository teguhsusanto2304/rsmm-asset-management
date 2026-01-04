<aside class="flex w-64 flex-col border-r border-gray-200 bg-white p-6">
    <!-- Logo -->
    <div class="flex items-center gap-4 pb-8">
        <div class="size-6 text-primary">
            <svg viewBox="0 0 48 48" fill="currentColor">
                <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4Z"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold">{{ config('app.name', 'AssetMinds') }}</h2>
    </div>

    <nav class="flex flex-1 flex-col justify-between">
        <!-- MAIN MENU -->
        <div class="flex flex-col gap-2">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold
               {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>

            <!-- MASTER DATA -->
            <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }" class="flex flex-col">
                <!-- Parent -->
                <button
                    @click="open = !open"
                    type="button"
                    class="flex items-center justify-between gap-3 rounded-lg px-4 py-2 text-sm font-medium
                    text-gray-600 hover:bg-gray-100"
                >
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">database</span>
                        Master Data
                    </div>
                    <span class="material-symbols-outlined text-base"
                          x-text="open ? 'expand_less' : 'expand_more'"></span>
                </button>

                <!-- Sub Menu -->
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('users.index') }}"
                       class="rounded-md px-3 py-2 text-sm
                       {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Manajemen User
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('departments.index') }}"
                       class="rounded-md px-3 py-2 text-sm
                       {{ request()->routeIs('departments.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Departemen
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('locations.index') }}"
                       class="rounded-md px-3 py-2 text-sm
                       {{ request()->routeIs('locations.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Lokasi
                    </a>
                </div>
                <div x-show="open" x-collapse class="ml-9 mt-1 flex flex-col gap-1">
                    <a href="{{ route('categories.index') }}"
                       class="rounded-md px-3 py-2 text-sm
                       {{ request()->routeIs('categories.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Kategori
                    </a>
                </div>
            </div>

        </div>

        <!-- BOTTOM SECTION -->
        <div class="flex flex-col gap-4 border-t pt-4">
            @php $user = auth()->user(); @endphp

            <!-- User Info -->
            <div class="flex items-center gap-3">
                <div class="rounded-full size-10 bg-gray-200"></div>
                <div>
                    <p class="text-sm font-semibold">{{ $user->name }}</p>
                    <p class="text-xs text-gray-600">{{ $user->email }}</p>
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
                    Logout
                </button>
            </form>
        </div>
    </nav>
</aside>
