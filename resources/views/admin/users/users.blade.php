@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

<main class="flex-1 p-8">
<div class="w-full max-w-7xl mx-auto">
<div class="flex flex-wrap gap-2 mb-4">
<a class="text-text-light-secondary text-base font-medium leading-normal" href="#">Home</a>
<span class="text-text-light-secondary text-base font-medium leading-normal">/</span>
<a class="text-text-light-secondary text-base font-medium leading-normal" href="#">Master Data</a>
<span class="text-text-light-secondary text-base font-medium leading-normal">/</span>
<span class="text-text-light-primary text-base font-medium leading-normal">Manajemen User</span>
</div>
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
<p class="text-text-light-primary text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Daftar User</p>
<a href="{{ route('users.create') }}"
   class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors duration-200">
    <span class="material-symbols-outlined mr-2 text-lg">add</span>
    <span class="truncate">Tambah Data Baru</span>
</a>

</div>
<div class="bg-white rounded-xl p-6 border border-gray-200">
<div class="flex flex-wrap items-center justify-between gap-4 mb-5">
<div class="flex-grow max-w-md">
<label class="flex flex-col h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-text-light-secondary flex border border-gray-300 bg-gray-50 items-center justify-center pl-4 rounded-l-lg border-r-0">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light-primary focus:outline-0 focus:ring-primary/50 border-gray-300 bg-white h-full placeholder:text-text-light-secondary px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal focus:border-primary" placeholder="Cari berdasarkan nama atau email" value=""/>
</div>
</label>
</div>
<div class="flex gap-3 overflow-x-auto">
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 border border-gray-300">
<p class="text-text-light-primary text-sm font-medium leading-normal">Semua</p>
<span class="material-symbols-outlined text-text-light-secondary text-lg">arrow_drop_down</span>
</button>
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 border border-gray-300">
<p class="text-text-light-primary text-sm font-medium leading-normal">Admin</p>
<span class="material-symbols-outlined text-text-light-secondary text-lg">arrow_drop_down</span>
</button>
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 border border-gray-300">
<p class="text-text-light-primary text-sm font-medium leading-normal">Manager</p>
<span class="material-symbols-outlined text-text-light-secondary text-lg">arrow_drop_down</span>
</button>
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 border border-gray-300">
<p class="text-text-light-primary text-sm font-medium leading-normal">User</p>
<span class="material-symbols-outlined text-text-light-secondary text-lg">arrow_drop_down</span>
</button>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left text-sm text-gray-500">
<thead class="text-xs text-gray-700 uppercase bg-gray-50">
<tr>
<th class="px-6 py-3 rounded-l-lg" scope="col">User</th>
<th class="px-6 py-3" scope="col">Departemen</th>
<th class="px-6 py-3" scope="col">Permission Role</th>
<th class="px-6 py-3" scope="col">Status</th>
<th class="px-6 py-3 rounded-r-lg" scope="col">Actions</th>
</tr>
</thead>
<tbody>
<tbody>
@forelse($mngusers as $user)
<tr class="border-b border-gray-200">
    <td class="px-6 py-4 flex items-center gap-3">
        <img class="w-10 h-10 rounded-full"
             src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name='.$user->name }}">

        <div>
            <div>{{ $user->name }}</div>
            <div class="text-sm text-gray-500">{{ $user->email }}</div>
        </div>
    </td>

    <td class="px-6 py-4 text-gray-600">
        @if($user->department)
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">
                <span class="material-symbols-outlined text-xs">apartment</span>
                {{ $user->department->department }}
            </span>
        @else
            <span class="text-gray-400 text-sm">-</span>
        @endif
    </td>

    <td class="px-6 py-4 text-gray-600">
        @if($user->roles->count() > 0)
            <div class="flex flex-wrap gap-1">
                @foreach($user->roles->take(2) as $role)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                        <span class="material-symbols-outlined text-xs">check</span>
                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                    </span>
                @endforeach
                @if($user->roles->count() > 2)
                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                        +{{ $user->roles->count() - 2 }}
                    </span>
                @endif
            </div>
        @else
            <span class="text-gray-400 text-sm">Belum ada role</span>
        @endif
    </td>

    <td class="px-6 py-4">
        @if($user->status === 'active')
            <span class="bg-green-100 text-green-800 px-2.5 py-0.5 rounded-full text-xs">Active</span>
        @elseif($user->status === 'pending')
            <span class="bg-yellow-100 text-yellow-800 px-2.5 py-0.5 rounded-full text-xs">Pending</span>
        @else
            <span class="bg-red-100 text-red-800 px-2.5 py-0.5 rounded-full text-xs">Inactive</span>
        @endif
    </td>

    <td class="px-6 py-4">
        <div class="flex gap-4">
            <a href="{{ route('users.edit', $user) }}" class="text-primary">
                <span class="material-symbols-outlined">edit</span>
            </a>

            <form method="POST" action="{{ route('users.destroy', $user) }}">
                @csrf @method('DELETE')
                <button class="text-red-500">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-6 text-gray-500">
        No users found
    </td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
</main>
@endsection
