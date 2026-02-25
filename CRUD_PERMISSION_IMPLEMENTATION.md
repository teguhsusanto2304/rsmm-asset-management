# CRUD Permission Implementation Guide

This guide shows how to implement role-based permission checks for Create, Edit, and Delete buttons/links in your Blade views.

## Quick Overview

The system provides three ways to check permissions:

1. **@canAccessFeature()** - Feature-based (Recommended) - Uses `config/menus.php` features
2. **@hasRole()** - Role-based checks - Direct role checking
3. **@hasPermission()** - Permission-based checks - Direct permission checking

## 1. Asset Management View Example

### Index View (List with Action Buttons)
File: `resources/views/admin/assets/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black">Daftar Asset</h1>
            <p class="text-gray-600">Kelola semua asset yang terdaftar di sistem.</p>
        </div>

        <div class="flex gap-2">
            {{-- IMPORT BUTTON - Only if user can import assets --}}
            @canAccessFeature('asset.import')
                <a href="{{ route('assets.import') }}" class="btn btn-success">
                    <i class="material-icons">upload_file</i> Import Asset
                </a>
            @endcanAccessFeature

            {{-- CREATE BUTTON - Only if user can create assets --}}
            @canAccessFeature('asset.create')
                <a href="{{ route('assets.create') }}" class="btn btn-primary">
                    <i class="material-icons">add</i> Tambah Asset
                </a>
            @endcanAccessFeature
        </div>
    </div>

    {{-- List Table --}}
    <div class="bg-white rounded-lg shadow p-4">
        <table class="w-full">
            <thead>
                <tr>
                    <th>Nama Asset</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->name }}</td>
                        <td>{{ $asset->category->name }}</td>
                        <td>{{ $asset->status }}</td>
                        <td>
                            <div class="flex gap-2">
                                {{-- VIEW BUTTON - All authenticated users --}}
                                <a href="{{ route('assets.show', $asset) }}" 
                                   class="text-blue-600 hover:underline">
                                    <i class="material-icons">visibility</i>
                                </a>

                                {{-- EDIT BUTTON - Only if user can edit assets --}}
                                @canAccessFeature('asset.edit')
                                    <a href="{{ route('assets.edit', $asset) }}" 
                                       class="text-yellow-600 hover:underline">
                                        <i class="material-icons">edit</i>
                                    </a>
                                @endcanAccessFeature

                                {{-- ASSIGN BUTTON - Only if user can assign assets --}}
                                @canAccessFeature('asset.assign')
                                    <a href="{{ route('assets.assign', $asset) }}" 
                                       class="text-green-600 hover:underline">
                                        <i class="material-icons">assignment</i>
                                    </a>
                                @endcanAccessFeature

                                {{-- DELETE BUTTON - If delete action is available --}}
                                @canAccessFeature('asset.delete')
                                    <form method="POST" 
                                          action="{{ route('assets.destroy', $asset) }}" 
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Hapus asset ini?')">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </form>
                                @endcanAccessFeature
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
```

### Show View (Detail View)
File: `resources/views/admin/assets/show.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">{{ $asset->name }}</h1>
        
        <div class="flex gap-2">
            {{-- EDIT BUTTON --}}
            @canAccessFeature('asset.edit')
                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning">
                    <i class="material-icons">edit</i> Edit
                </a>
            @endcanAccessFeature

            {{-- DELETE BUTTON --}}
            @canAccessFeature('asset.delete')
                <form method="POST" action="{{ route('assets.destroy', $asset) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Hapus asset ini?')">
                        <i class="material-icons">delete</i> Hapus
                    </button>
                </form>
            @endcanAccessFeature
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Informasi Asset</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-bold text-gray-700">Asset Tag</label>
                <p>{{ $asset->asset_tag }}</p>
            </div>
            <div>
                <label class="font-bold text-gray-700">Serial Number</label>
                <p>{{ $asset->serial_number }}</p>
            </div>
            <div>
                <label class="font-bold text-gray-700">Kategori</label>
                <p>{{ $asset->category->name }}</p>
            </div>
            <div>
                <label class="font-bold text-gray-700">Status</label>
                <p>{{ $asset->status }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 2. User Management View Example

### Index View
File: `resources/views/admin/users/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Manajemen User</h1>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('user.create')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="material-icons">add</i> Tambah User
            </a>
        @endcanAccessFeature
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                {{ $user->roles->first()?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                {{-- VIEW --}}
                                <a href="{{ route('users.show', $user) }}" 
                                   class="text-blue-600 hover:underline">Detail</a>

                                {{-- EDIT --}}
                                @canAccessFeature('user.edit')
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="text-yellow-600 hover:underline">Edit</a>
                                @endcanAccessFeature

                                {{-- DELETE --}}
                                @canAccessFeature('user.delete')
                                    <form method="POST" 
                                          action="{{ route('users.destroy', $user) }}"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Hapus user?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endcanAccessFeature
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            Belum ada user terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

## 3. Department Management View Example

### Index View
File: `resources/views/admin/departments/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Manajemen Departemen</h1>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('department.create')
            <a href="{{ route('departments.create') }}" class="btn btn-primary">
                <i class="material-icons">add</i> Tambah Departemen
            </a>
        @endcanAccessFeature
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nama Departemen</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $dept->name }}</td>
                        <td class="px-4 py-2">{{ $dept->description }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                {{-- EDIT --}}
                                @canAccessFeature('department.edit')
                                    <a href="{{ route('departments.edit', $dept) }}" 
                                       class="text-yellow-600 hover:underline">Edit</a>
                                @endcanAccessFeature

                                {{-- DELETE --}}
                                @canAccessFeature('department.delete')
                                    <form method="POST" 
                                          action="{{ route('departments.destroy', $dept) }}"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Hapus departemen?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endcanAccessFeature
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                            Belum ada departemen terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

## 4. Category Management View Example

### Index View
File: `resources/views/admin/categories/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Manajemen Kategori</h1>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('category.create')
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="material-icons">add</i> Tambah Kategori
            </a>
        @endcanAccessFeature
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nama Kategori</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $cat->name }}</td>
                        <td class="px-4 py-2">{{ $cat->description }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                {{-- EDIT --}}
                                @canAccessFeature('category.edit')
                                    <a href="{{ route('categories.edit', $cat) }}" 
                                       class="text-yellow-600 hover:underline">Edit</a>
                                @endcanAccessFeature

                                {{-- DELETE --}}
                                @canAccessFeature('category.delete')
                                    <form method="POST" 
                                          action="{{ route('categories.destroy', $cat) }}"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Hapus kategori?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endcanAccessFeature
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                            Belum ada kategori terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

## 5. Location Management View Example

### Index View
File: `resources/views/admin/locations/index.blade.php`

```blade
@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Manajemen Lokasi</h1>

        {{-- CREATE BUTTON --}}
        @canAccessFeature('location.create')
            <a href="{{ route('locations.create') }}" class="btn btn-primary">
                <i class="material-icons">add</i> Tambah Lokasi
            </a>
        @endcanAccessFeature
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nama Lokasi</th>
                    <th class="px-4 py-2 text-left">Alamat</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $loc)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $loc->name }}</td>
                        <td class="px-4 py-2">{{ $loc->address }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                {{-- EDIT --}}
                                @canAccessFeature('location.edit')
                                    <a href="{{ route('locations.edit', $loc) }}" 
                                       class="text-yellow-600 hover:underline">Edit</a>
                                @endcanAccessFeature

                                {{-- DELETE --}}
                                @canAccessFeature('location.delete')
                                    <form method="POST" 
                                          action="{{ route('locations.destroy', $loc) }}"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Hapus lokasi?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endcanAccessFeature
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                            Belum ada lokasi terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

## Permission Directives Reference

### @canAccessFeature('feature.name')
Feature-based check using `config/menus.php`

```blade
@canAccessFeature('asset.create')
    <a href="{{ route('assets.create') }}">Tambah Asset</a>
@endcanAccessFeature
```

**Features defined in config/menus.php:**
- `asset.create`, `asset.edit`, `asset.delete`, `asset.import`, `asset.assign`, `asset.transfer`
- `user.create`, `user.edit`, `user.delete`
- `department.create`, `department.edit`, `department.delete`
- `location.create`, `location.edit`, `location.delete`
- `category.create`, `category.edit`, `category.delete`
- `maintenance.create`, `maintenance.edit`, `maintenance.complete`, `maintenance.assign`

---

### @hasRole('role.name')
Role-based check

```blade
{{-- Single role --}}
@hasRole('admin')
    <button>Admin Only</button>
@endhasRole

{{-- Multiple roles (OR logic) --}}
@hasRole(['admin', 'manager'])
    <button>Admin or Manager</button>
@endhasRole
```

**Available roles:**
- `admin` - Full system access
- `direktur` - Director level access
- `manager` - Department/asset management
- `supervisor` - Limited management
- `staff` - Basic employee access
- `technician` - Maintenance operations

---

### @hasPermission('permission.name')
Permission-based check

```blade
{{-- Single permission --}}
@hasPermission('edit_asset')
    <a href="{{ route('assets.edit', $asset) }}">Edit</a>
@endhasPermission

{{-- Multiple permissions --}}
@hasPermission(['view_asset', 'edit_asset'])
    <div>Can view or edit</div>
@endhasPermission
```

**Common permissions:**
- `view_asset`, `create_asset`, `edit_asset`, `delete_asset`
- `view_user`, `create_user`, `edit_user`, `delete_user`
- `view_department`, `create_department`, `edit_department`, `delete_department`
- `view_location`, `create_location`, `edit_location`, `delete_location`
- `view_category`, `create_category`, `edit_category`, `delete_category`

---

## Route Protection with Middleware

All routes should be protected with `check.feature` middleware:

```php
// In routes/web.php

Route::middleware(['auth'])->group(function () {
    // GET/VIEW - No middleware needed
    Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');

    // CREATE
    Route::get('assets/create', [AssetController::class, 'create'])
        ->middleware('check.feature:asset.create')->name('assets.create');
    Route::post('assets', [AssetController::class, 'store'])
        ->middleware('check.feature:asset.create')->name('assets.store');

    // EDIT
    Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])
        ->middleware('check.feature:asset.edit')->name('assets.edit');
    Route::put('assets/{asset}', [AssetController::class, 'update'])
        ->middleware('check.feature:asset.edit')->name('assets.update');

    // DELETE
    Route::delete('assets/{asset}', [AssetController::class, 'destroy'])
        ->middleware('check.feature:asset.delete')->name('assets.destroy');
});
```

---

## Best Practices

1. **Always check in both View AND Route**
   - View checks hide UI elements (better UX)
   - Route checks prevent unauthorized access (security)

2. **Use @canAccessFeature for CRUD operations**
   - Most flexible approach
   - Centralized in config file
   - Easy to maintain

3. **Use @hasRole for admin-only areas**
   - Simple and clear
   - Good for administrative sections

4. **Never rely ONLY on view-level checks**
   - Always protect routes with middleware
   - Always check in controller if needed

5. **Consistent feature naming**
   - Create: `feature.create`
   - Edit: `feature.edit`
   - Delete: `feature.delete`

---

## Testing Permissions in Tinker

```bash
php artisan tinker
```

```php
// Get user
$user = User::find(1);

// Check roles
$user->hasRole('admin');
$user->hasRole(['admin', 'manager']);

// Check permissions
$user->hasPermissionTo('create_asset');
$user->hasPermissionTo(['create_asset', 'edit_asset']);

// Get accessible features
$service = app(\App\Services\MenuService::class);
$service->canAccessFeature('asset.create', $user);
$service->canAccessFeature('asset.edit', $user);
$service->canAccessFeature('asset.delete', $user);
```

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Button showing when it shouldn't | Check `config/menus.php` features config |
| 403 Forbidden on route | Check `routes/web.php` middleware |
| Users can't see button but role looks right | Clear permission cache: `php artisan cache:clear` |
| Feature not defined | Add to `config/menus.php` features array |
| Role not found | Check if role exists in database, run seeders |

---

## Summary

The implementation uses:
- **Views**: `@canAccessFeature()` Blade directive to show/hide UI elements
- **Routes**: `check.feature:feature.name` middleware to protect endpoints
- **Config**: `config/menus.php` features array defines permissions by feature
- **Security**: Both view AND route protection ensures security

This provides a flexible, maintainable permission system that's easy to update and audit.
