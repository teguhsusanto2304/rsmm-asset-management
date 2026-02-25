# Complete View Implementation Examples

Copy-paste ready examples for all CRUD views with permission checks.

---

## 1. Users Index View

**File:** `resources/views/admin/users/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Manajemen User</h1>
            <p class="text-[#617989]">Kelola semua user yang terdaftar di sistem.</p>
        </div>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('user.create')
            <a href="{{ route('users.create') }}"
               class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">add</span>
                Tambah User
            </a>
        @endcanAccessFeature
    </div>

    <x-alert />

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
        {{-- Search Form --}}
        <form method="GET" class="mb-4 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email user"
                   class="form-input w-full rounded-lg" />
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold">
                Cari
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f8f9fa] text-xs uppercase text-[#617989]">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Departemen</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#f8fbff]">
                            <td class="p-3">
                                <div class="font-semibold text-[#111518]">{{ $user->name }}</div>
                            </td>
                            <td class="p-3">{{ $user->email }}</td>
                            <td class="p-3">{{ $user->department?->name ?? '-' }}</td>
                            <td class="p-3">
                                @php
                                    $roleName = $user->roles->first()?->name;
                                    $roleColors = [
                                        'admin' => 'bg-red-50 text-red-700',
                                        'direktur' => 'bg-purple-50 text-purple-700',
                                        'manager' => 'bg-blue-50 text-blue-700',
                                        'supervisor' => 'bg-green-50 text-green-700',
                                        'staff' => 'bg-gray-50 text-gray-700',
                                        'technician' => 'bg-orange-50 text-orange-700',
                                    ];
                                @endphp
                                @if($roleName)
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $roleColors[$roleName] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($user->status === 'active')
                                    <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-bold">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-bold">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- VIEW --}}
                                    <a href="{{ route('users.show', $user) }}"
                                       class="text-gray-600 hover:bg-gray-100 p-1 rounded"
                                       title="Detail">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>

                                    {{-- EDIT --}}
                                    @canAccessFeature('user.edit')
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-primary hover:bg-primary/10 p-1 rounded"
                                           title="Edit">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                    @endcanAccessFeature

                                    {{-- DELETE --}}
                                    @canAccessFeature('user.delete')
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:bg-red-100 p-1 rounded"
                                                    onclick="return confirm('Hapus user ini?')" title="Hapus">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @endcanAccessFeature
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#617989]">
                                Belum ada user terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
```

---

## 2. Departments Index View

**File:** `resources/views/admin/departments/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Manajemen Departemen</h1>
            <p class="text-[#617989]">Kelola struktur departemen perusahaan.</p>
        </div>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('department.create')
            <a href="{{ route('departments.create') }}"
               class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">add</span>
                Tambah Departemen
            </a>
        @endcanAccessFeature
    </div>

    <x-alert />

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f8f9fa] text-xs uppercase text-[#617989]">
                    <tr>
                        <th class="p-3 text-left">Nama Departemen</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-left">Jumlah Staff</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($departments as $dept)
                        <tr class="hover:bg-[#f8fbff]">
                            <td class="p-3">
                                <div class="font-semibold text-[#111518]">{{ $dept->name }}</div>
                            </td>
                            <td class="p-3 text-[#617989]">{{ $dept->description ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-bold">
                                    {{ $dept->users_count ?? 0 }} staff
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- VIEW --}}
                                    <a href="{{ route('departments.show', $dept) }}"
                                       class="text-gray-600 hover:bg-gray-100 p-1 rounded">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>

                                    {{-- EDIT --}}
                                    @canAccessFeature('department.edit')
                                        <a href="{{ route('departments.edit', $dept) }}"
                                           class="text-primary hover:bg-primary/10 p-1 rounded">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                    @endcanAccessFeature

                                    {{-- DELETE --}}
                                    @canAccessFeature('department.delete')
                                        <form method="POST" action="{{ route('departments.destroy', $dept) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:bg-red-100 p-1 rounded"
                                                    onclick="return confirm('Hapus departemen? Pastikan tidak ada staff di departemen ini.')">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @endcanAccessFeature
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-[#617989]">
                                Belum ada departemen terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

---

## 3. Locations Index View

**File:** `resources/views/admin/locations/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Manajemen Lokasi</h1>
            <p class="text-[#617989]">Kelola lokasi/ruangan penyimpanan asset.</p>
        </div>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('location.create')
            <a href="{{ route('locations.create') }}"
               class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">add</span>
                Tambah Lokasi
            </a>
        @endcanAccessFeature
    </div>

    <x-alert />

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f8f9fa] text-xs uppercase text-[#617989]">
                    <tr>
                        <th class="p-3 text-left">Nama Lokasi</th>
                        <th class="p-3 text-left">Alamat</th>
                        <th class="p-3 text-left">Total Asset</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($locations as $loc)
                        <tr class="hover:bg-[#f8fbff]">
                            <td class="p-3">
                                <div class="font-semibold text-[#111518]">{{ $loc->name }}</div>
                            </td>
                            <td class="p-3 text-[#617989]">{{ $loc->address ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-bold">
                                    {{ $loc->assets_count ?? 0 }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- VIEW --}}
                                    <a href="{{ route('locations.show', $loc) }}"
                                       class="text-gray-600 hover:bg-gray-100 p-1 rounded">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>

                                    {{-- EDIT --}}
                                    @canAccessFeature('location.edit')
                                        <a href="{{ route('locations.edit', $loc) }}"
                                           class="text-primary hover:bg-primary/10 p-1 rounded">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                    @endcanAccessFeature

                                    {{-- DELETE --}}
                                    @canAccessFeature('location.delete')
                                        <form method="POST" action="{{ route('locations.destroy', $loc) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:bg-red-100 p-1 rounded"
                                                    onclick="return confirm('Hapus lokasi?')">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @endcanAccessFeature
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-[#617989]">
                                Belum ada lokasi terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

---

## 4. Categories Index View

**File:** `resources/views/admin/categories/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Manajemen Kategori</h1>
            <p class="text-[#617989]">Kelola kategori/jenis asset di sistem.</p>
        </div>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('category.create')
            <a href="{{ route('categories.create') }}"
               class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">add</span>
                Tambah Kategori
            </a>
        @endcanAccessFeature
    </div>

    <x-alert />

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f8f9fa] text-xs uppercase text-[#617989]">
                    <tr>
                        <th class="p-3 text-left">Nama Kategori</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-left">Total Asset</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-[#f8fbff]">
                            <td class="p-3">
                                <div class="font-semibold text-[#111518]">{{ $cat->name }}</div>
                            </td>
                            <td class="p-3 text-[#617989]">{{ $cat->description ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded text-xs font-bold">
                                    {{ $cat->assets_count ?? 0 }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- VIEW --}}
                                    <a href="{{ route('categories.show', $cat) }}"
                                       class="text-gray-600 hover:bg-gray-100 p-1 rounded">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>

                                    {{-- EDIT --}}
                                    @canAccessFeature('category.edit')
                                        <a href="{{ route('categories.edit', $cat) }}"
                                           class="text-primary hover:bg-primary/10 p-1 rounded">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                    @endcanAccessFeature

                                    {{-- DELETE --}}
                                    @canAccessFeature('category.delete')
                                        <form method="POST" action="{{ route('categories.destroy', $cat) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:bg-red-100 p-1 rounded"
                                                    onclick="return confirm('Hapus kategori?')">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @endcanAccessFeature
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-[#617989]">
                                Belum ada kategori terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

---

## 5. Show Views Pattern (Detail Halaman)

### Generic Pattern for All Show Views

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-black text-[#111518]">{{ $entity->name }}</h1>
        
        <div class="flex gap-2">
            {{-- EDIT BUTTON --}}
            @canAccessFeature('entity.edit')
                <a href="{{ route('entity.edit', $entity) }}"
                   class="flex items-center gap-2 rounded-lg h-10 px-4 bg-yellow-600 text-white text-sm font-bold hover:bg-yellow-700">
                    <span class="material-symbols-outlined">edit</span>
                    Edit
                </a>
            @endcanAccessFeature

            {{-- DELETE BUTTON --}}
            @canAccessFeature('entity.delete')
                <form method="POST" action="{{ route('entity.destroy', $entity) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="flex items-center gap-2 rounded-lg h-10 px-4 bg-red-600 text-white text-sm font-bold hover:bg-red-700"
                            onclick="return confirm('Hapus item ini secara permanen?')">
                        <span class="material-symbols-outlined">delete</span>
                        Hapus
                    </button>
                </form>
            @endcanAccessFeature
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
        <h2 class="text-xl font-bold mb-4">Informasi Detail</h2>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-[#617989] mb-1">Field 1</label>
                <p class="text-[#111518]">{{ $entity->field1 }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#617989] mb-1">Field 2</label>
                <p class="text-[#111518]">{{ $entity->field2 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## Implementation Checklist

- [ ] Update assets/index.blade.php - DONE ✅
- [ ] Update users/index.blade.php
- [ ] Update departments/index.blade.php
- [ ] Update locations/index.blade.php
- [ ] Update categories/index.blade.php
- [ ] Update all show.blade.php files (users, departments, locations, categories)
- [ ] Routes already protected ✅

---

## Quick Copy-Paste Pattern

For any entity (replace `entity` with actual name):

```blade
{{-- CREATE BUTTON --}}
@canAccessFeature('entity.create')
    <a href="{{ route('entity.create') }}" class="btn btn-primary">
        <i class="material-icons">add</i> Create
    </a>
@endcanAccessFeature

{{-- EDIT BUTTON (in table row) --}}
@canAccessFeature('entity.edit')
    <a href="{{ route('entity.edit', $item) }}" class="btn btn-warning">
        <i class="material-icons">edit</i>
    </a>
@endcanAccessFeature

{{-- DELETE BUTTON (in table row) --}}
@canAccessFeature('entity.delete')
    <form method="POST" action="{{ route('entity.destroy', $item) }}" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Confirm delete?')">
            <i class="material-icons">delete</i>
        </button>
    </form>
@endcanAccessFeature
```

---

## Notes

- All examples use Tailwind CSS classes (matching your current design)
- All examples use Material Icons
- All examples include proper confirmation alerts for delete
- All examples follow your current UI pattern
- Features are configured in `config/menus.php`
- Routes are protected in `routes/web.php`

Just copy and paste into your views!
