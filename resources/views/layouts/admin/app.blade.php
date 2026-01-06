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

<body class="bg-background-light font-display">

<div class="relative flex min-h-screen w-full">

    {{-- Sidebar --}}
    @include('layouts.admin.partials.sidebar')

    <div class="flex flex-1 flex-col">

        {{-- Header --}}
        @include('layouts.admin.partials.header')

        {{-- Main Content --}}
        <main class="flex-1 bg-gray-50 px-10 py-8">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.admin.partials.footer')

    </div>
</div>

</body>
</html>
