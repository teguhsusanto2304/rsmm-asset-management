<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1392ec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111518",
                    },
                    fontFamily: {
                        display: ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background-light font-display" x-data="{ sidebarOpen: false }">

<div class="relative flex min-h-screen w-full" x-data="{ sidebarOpen: false }">

    {{-- Sidebar (Desktop Only) --}}
    <div class="hidden lg:flex lg:w-64 flex-col">
        @include('layouts.admin.partials.sidebar')
    </div>

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"
         x-transition>
    </div>

    {{-- Mobile Sidebar --}}
    <div x-show="sidebarOpen"
         class="fixed left-0 top-0 z-50 h-screen w-64 overflow-y-auto lg:hidden"
         x-transition>
        @include('layouts.admin.partials.sidebar')
    </div>

    <div class="flex flex-1 flex-col w-full">

        {{-- Header --}}
        <div @toggle-sidebar.window="sidebarOpen = !sidebarOpen">
            @include('layouts.admin.partials.header')
        </div>

        {{-- Main Content --}}
        <main class="flex-1 bg-gray-50 px-4 sm:px-6 lg:px-10 py-6 sm:py-8">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.admin.partials.footer')

    </div>
</div>

<script>
    // Close sidebar when navigating on mobile
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (link && window.innerWidth < 1024) {
            document.dispatchEvent(new CustomEvent('toggle-sidebar'));
        }
    });
</script>

</body>
</html>
