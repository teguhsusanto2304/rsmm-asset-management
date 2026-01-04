@extends('layouts.admin.app')

@section('content')
<main class="flex-1 flex flex-col h-full overflow-hidden">

    {{-- HEADER --}}
    <header class="bg-white border-b border-[#dbe1e6] px-8 py-5">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col gap-4 mb-4">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm">
                    <a href="{{ route('dashboard') }}" class="text-[#617989] hover:text-primary">Beranda</a>
                    <span class="material-symbols-outlined text-[#dbe1e6] text-[16px]">chevron_right</span>
                    <span class="text-[#111518] font-medium">Master Data: Kategori</span>
                </div>

                {{-- Title --}}
                <div class="flex justify-between items-end">
                    <div>
                        <h2 class="text-3xl font-black text-[#111518]">Master Data: Kategori</h2>
                        <p class="text-[#617989]">
                            Kelola struktur dan hierarki Kategori.
                        </p>
                    </div>

                    <a href="{{ route('categories.create') }}"
                       class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90">
                        <span class="material-symbols-outlined">add</span>
                        Tambah Kategori
                    </a>
                </div>

            </div>
            {{-- ❌ Error Alert --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-red-600">error</span>
                <h4 class="font-bold text-red-700">Terjadi Kesalahan</h4>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Success Alert --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <h4 class="font-bold text-green-700">Berhasil</h4>
            </div>
            <p class="text-sm text-green-700">
                {{ session('success') }}
            </p>
        </div>
    @endif
        </div>
    </header>


    {{-- CONTENT --}}
    <div class="flex-1 overflow-y-auto p-8 bg-[#f8f9fa]">
        <div class="max-w-7xl mx-auto flex gap-6">
        

            {{-- LEFT: DEPARTMENT TREE --}}
            <aside class="w-80 bg-white rounded-xl border border-[#dbe1e6] overflow-hidden">
                <div class="p-4 border-b font-bold">Struktur Hierarki</div>

                <div class="p-2 overflow-y-auto">
                    <ul class="space-y-1 text-sm">
                        @foreach ($categories as $loc)
                            @include('admin.categories.partials.tree', ['category' => $loc])
                        @endforeach
                    </ul>
                </div>
            </aside>

            {{-- RIGHT: SUB UNIT TABLE --}}
            <section class="flex-1 bg-white rounded-xl border border-[#dbe1e6] overflow-hidden">

                {{-- HEADER --}}
                <div class="p-4 border-b flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg">
                            @if(isset($selectCategory))
                            {{ optional($selectCategory)->name ?? 'Pilih Kategori' }}
                            @endif
                        </h3>
                        <span class="text-xs text-primary font-bold">
                            {{ $subUnits->count() }} Sub-Unit
                        </span>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#f8f9fa] text-xs uppercase text-[#617989]">
                            <tr>
                                <th class="p-4">Nama Unit</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">

                            @forelse ($subUnits as $unit)
                                <tr class="hover:bg-[#f8fbff]">
                                    
                                    <td class="p-4 font-bold">
                                        {{ $unit->name }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold
                                            {{ $unit->status === 'active'
                                                ? 'bg-green-50 text-green-700'
                                                : 'bg-yellow-50 text-yellow-700' }}">
                                            {{ ucfirst($unit->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('categories.edit', $unit) }}"
                                               class="text-primary hover:bg-primary/10 p-1 rounded">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('categories.destroy', $unit) }}">
                                                @csrf @method('DELETE')
                                                <button
                                                    onclick="return confirm('Hapus data?')"
                                                    class="text-red-600 hover:bg-red-50 p-1 rounded">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-[#617989]">
                                        Tidak ada sub-unit
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </section>

        </div>
    </div>
</main>
@endsection
