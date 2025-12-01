<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Halaman Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1392ec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111518",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>
<body class="bg-background-light font-display">
<div class="relative flex h-auto min-h-screen w-full flex-row group/design-root overflow-x-hidden">
    <aside class="flex w-64 flex-col border-r border-gray-200 bg-white p-6">
        <div class="flex items-center gap-4 text-gray-900 pb-8">
            <div class="size-6 text-primary">
                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
                </svg>
            </div>
            <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">AssetManager</h2>
        </div>

        <nav class="flex flex-1 flex-col justify-between">
            <div class="flex flex-col gap-2">
                <a class="flex items-center gap-3 rounded-lg bg-primary/10 px-4 py-2 text-primary text-sm font-bold" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-base">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 text-sm font-medium" href="#">
                    <span class="material-symbols-outlined text-base">list_alt</span>
                    <span>Daftar Aset</span>
                </a>
                <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 text-sm font-medium" href="#">
                    <span class="material-symbols-outlined text-base">analytics</span>
                    <span>Laporan</span>
                </a>
                <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 text-sm font-medium" href="#">
                    <span class="material-symbols-outlined text-base">settings</span>
                    <span>Pengaturan</span>
                </a>
            </div>

            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-red-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-600 transition-colors">
                        <span class="truncate">Logout</span>
                    </button>
                </form>

                <div class="flex items-center gap-3 border-t border-gray-200 pt-4">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                         style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBgCVXGnzZeQaiHniRrw0sQ3HfWOPv8YjqlSxgJMGr2BO9hblVdtPDFF16egHkjoXmOZ2gSG9eMsW6Bg9Atf3fuUvdOsJp9zMzMCI6aH-TZAvAVynNaxLgLWe4P0iQuDtrTHZSgDNUFydnhvgJqIZIQD4AbPGcOE9dRn-UB5uknMnmeam5Mdl1Sftt-Xw2tNZfZ_Hx1usdG0ztTQs-2rfSjzct31wE284G8qmxIKsgdua1YtrvhPcRJ594u_Z5rXdcOSHDqmFCMq1mT");'></div>
                    <div class="flex flex-col">
                        <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </nav>
    </aside>

    <div class="flex h-full grow flex-col">
        <main class="flex flex-1 flex-col bg-gray-50 px-10 py-8">
            <div class="layout-content-container flex w-full max-w-[1200px] flex-1 flex-col self-center">
                <div class="flex flex-wrap justify-between gap-3 p-4">
                    <p class="text-gray-900 text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Dashboard</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4">
                    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl bg-white p-6 shadow-sm">
                        <p class="text-gray-600 text-base font-medium leading-normal">Total Aset</p>
                        <p class="text-gray-900 tracking-light text-2xl font-bold leading-tight">{{ $stats['total_assets'] }}</p>
                    </div>
                    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl bg-white p-6 shadow-sm">
                        <p class="text-gray-600 text-base font-medium leading-normal">Aset Dimutasi</p>
                        <p class="text-gray-900 tracking-light text-2xl font-bold leading-tight">{{ $stats['mutated_assets'] }}</p>
                    </div>
                    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl bg-white p-6 shadow-sm">
                        <p class="text-gray-600 text-base font-medium leading-normal">Aset Rusak</p>
                        <p class="text-gray-900 tracking-light text-2xl font-bold leading-tight">{{ $stats['damaged_assets'] }}</p>
                    </div>
                    <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl bg-white p-6 shadow-sm">
                        <p class="text-gray-600 text-base font-medium leading-normal">Dalam Penanganan</p>
                        <p class="text-gray-900 tracking-light text-2xl font-bold leading-tight">{{ $stats['in_handling'] }}</p>
                    </div>
                </div>

                <!-- Latest Mutation Requests -->
                <h2 class="text-gray-900 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Pengajuan Mutasi Terbaru</h2>
                <div class="px-4 py-3 @container">
                    <div class="flex overflow-hidden rounded-xl bg-white shadow-sm">
                        <table class="flex-1 w-full">
                            <thead>
                            <tr class="border-b border-b-gray-200">
                                <th class="px-4 py-3 text-left text-gray-600 w-[15%] text-sm font-medium leading-normal">Request ID</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[25%] text-sm font-medium leading-normal">Asset Name</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[20%] text-sm font-medium leading-normal">From Location</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[20%] text-sm font-medium leading-normal">To Location</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[10%] text-sm font-medium leading-normal">Status</th>
                                <th class="px-4 py-3 text-left text-gray-400 w-[10%] text-sm font-medium leading-normal">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestMutations as $mutation)
                                <tr class="border-t border-t-gray-200">
                                    <td class="h-[72px] px-4 py-2 text-gray-500 text-sm font-normal leading-normal">MUT-{{ sprintf('%03d', $mutation->id) }}</td>
                                    <td class="h-[72px] px-4 py-2 text-gray-800 text-sm font-normal leading-normal">{{ $mutation->asset->name }}</td>
                                    <td class="h-[72px] px-4 py-2 text-gray-500 text-sm font-normal leading-normal">{{ $mutation->fromLocation->name }}</td>
                                    <td class="h-[72px] px-4 py-2 text-gray-500 text-sm font-normal leading-normal">{{ $mutation->toLocation->name }}</td>
                                    <td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal">{!! $mutation->status_badge !!}</td>
                                    <td class="h-[72px] px-4 py-2 text-primary text-sm font-bold leading-normal tracking-[0.015em] cursor-pointer hover:underline">
                                        <a href="#">View Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t border-t-gray-200">
                                    <td colspan="6" class="h-[72px] px-4 py-2 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Latest Damage Reports -->
                <h2 class="text-gray-900 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Laporan Aset Rusak Terbaru</h2>
                <div class="px-4 py-3 @container">
                    <div class="flex overflow-hidden rounded-xl bg-white shadow-sm">
                        <table class="flex-1 w-full">
                            <thead>
                            <tr class="border-b border-b-gray-200">
                                <th class="px-4 py-3 text-left text-gray-600 w-[15%] text-sm font-medium leading-normal">Report ID</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[35%] text-sm font-medium leading-normal">Asset Name</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[20%] text-sm font-medium leading-normal">Reporter</th>
                                <th class="px-4 py-3 text-left text-gray-600 w-[20%] text-sm font-medium leading-normal">Status</th>
                                <th class="px-4 py-3 text-left text-gray-400 w-[10%] text-sm font-medium leading-normal">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestDamages as $damage)
                                <tr class="border-t border-t-gray-200">
                                    <td class="h-[72px] px-4 py-2 text-gray-500 text-sm font-normal leading-normal">DMG-{{ sprintf('%03d', $damage->id) }}</td>
                                    <td class="h-[72px] px-4 py-2 text-gray-800 text-sm font-normal leading-normal">{{ $damage->asset->name }}</td>
                                    <td class="h-[72px] px-4 py-2 text-gray-500 text-sm font-normal leading-normal">{{ $damage->reporter->name }}</td>
                                    <td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal">{!! $damage->status_badge !!}</td>
                                    <td class="h-[72px] px-4 py-2 text-primary text-sm font-bold leading-normal tracking-[0.015em] cursor-pointer hover:underline">
                                        <a href="#">View Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t border-t-gray-200">
                                    <td colspan="5" class="h-[72px] px-4 py-2 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
</body>
</html>