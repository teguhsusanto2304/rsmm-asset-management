# Permission Implementation - Quick Start Guide

## What Was Done

✅ **Asset Index View Updated** - Added permission checks for:
   - Import Asset button
   - Create Asset button  
   - Edit button (in table rows)
   - Assign button (in table rows)

✅ **Routes Protected** - Added `check.feature` middleware to all CRUD routes:
   - All Users, Departments, Locations, Categories routes protected
   - All Asset CRUD routes protected
   - All Maintenance routes protected

✅ **Comprehensive Guide Created** - See [CRUD_PERMISSION_IMPLEMENTATION.md](CRUD_PERMISSION_IMPLEMENTATION.md)

---

## How It Works

### 1. Feature-Based Permission Check (Recommended)

**In Blade Views:**
```blade
@canAccessFeature('asset.create')
    <a href="{{ route('assets.create') }}">Tambah Asset</a>
@endcanAccessFeature
```

**Features are defined in:** `config/menus.php` (features section)

**Example from config:**
```php
'features' => [
    'asset.create' => ['create_asset'],
    'asset.edit' => ['edit_asset'],
    'asset.delete' => ['delete_asset'],
    'user.create' => ['create_user'],
    'user.edit' => ['edit_user'],
    'user.delete' => ['delete_user'],
    // ... more features
]
```

### 2. Route Protection with Middleware

**In routes/web.php:**
```php
// CREATE
Route::get('assets/create', [AssetController::class, 'create'])
    ->middleware('check.feature:asset.create')
    ->name('assets.create');

Route::post('assets', [AssetController::class, 'store'])
    ->middleware('check.feature:asset.create')
    ->name('assets.store');

// EDIT
Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])
    ->middleware('check.feature:asset.edit')
    ->name('assets.edit');

Route::put('assets/{asset}', [AssetController::class, 'update'])
    ->middleware('check.feature:asset.edit')
    ->name('assets.update');

// DELETE
Route::delete('assets/{asset}', [AssetController::class, 'destroy'])
    ->middleware('check.feature:asset.delete')
    ->name('assets.destroy');
```

---

## Implementation Checklist

### For Each CRUD Entity (Asset, User, Department, etc.)

#### 1. Update Index View
Add permission checks to Create button:
```blade
@canAccessFeature('asset.create')
    <a href="{{ route('assets.create') }}" class="btn btn-primary">
        <i class="material-icons">add</i> Tambah Asset
    </a>
@endcanAccessFeature
```

Add permission checks to Edit/Delete buttons in table rows:
```blade
@canAccessFeature('asset.edit')
    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning">
        <i class="material-icons">edit</i>
    </a>
@endcanAccessFeature

@canAccessFeature('asset.delete')
    <form method="POST" action="{{ route('assets.destroy', $asset) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            Hapus
        </button>
    </form>
@endcanAccessFeature
```

#### 2. Update Show View
Add Edit/Delete buttons:
```blade
@canAccessFeature('asset.edit')
    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning">Ubah</a>
@endcanAccessFeature

@canAccessFeature('asset.delete')
    <form method="POST" action="{{ route('assets.destroy', $asset) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Hapus</button>
    </form>
@endcanAccessFeature
```

#### 3. Routes Already Protected ✅
Update your routes with middleware (ALREADY DONE for major entities):
```php
Route::get('assets/create', [...])
    ->middleware('check.feature:asset.create');
Route::post('assets', [...])
    ->middleware('check.feature:asset.create');
Route::put('assets/{asset}', [...])
    ->middleware('check.feature:asset.edit');
Route::delete('assets/{asset}', [...])
    ->middleware('check.feature:asset.delete');
```

---

## Feature Names Reference

### Assets
- `asset.create` - Create new asset
- `asset.edit` - Edit existing asset
- `asset.delete` - Delete asset
- `asset.import` - Import assets from file
- `asset.assign` - Assign asset to user
- `asset.transfer` - Transfer asset between departments

### Users
- `user.create` - Create new user
- `user.edit` - Edit user
- `user.delete` - Delete user

### Departments
- `department.create` - Create department
- `department.edit` - Edit department
- `department.delete` - Delete department

### Locations  
- `location.create` - Create location
- `location.edit` - Edit location
- `location.delete` - Delete location

### Categories
- `category.create` - Create category
- `category.edit` - Edit category
- `category.delete` - Delete category

### Maintenance
- `maintenance.create` - Create maintenance record
- `maintenance.edit` - Edit maintenance
- `maintenance.complete` - Complete maintenance
- `maintenance.assign` - Assign technician

---

## Three Ways to Check Permissions

### Option 1: @canAccessFeature (RECOMMENDED) ✅
```blade
@canAccessFeature('asset.create')
    <button>Tambah Asset</button>
@endcanAccessFeature
```
**Best for:** CRUD operations, most flexible, config-based
**Uses:** `config/menus.php` features

---

### Option 2: @hasRole
```blade
@hasRole(['admin', 'manager'])
    <button>Only Admin/Manager</button>
@endhasRole
```
**Best for:** Admin-only areas, role-based access
**Direct check:** No config needed

---

### Option 3: @hasPermission
```blade
@hasPermission('edit_asset')
    <a href="{{ route('assets.edit', $asset) }}">Edit</a>
@endhasPermission
```
**Best for:** Granular permission control, bypasses roles
**Direct check:** No config needed

---

## Testing Your Implementation

### Test if buttons show/hide correctly:
```bash
php artisan tinker
```

```php
// Get a user
$user = User::find(1);

// Check what role they have
$user->getRoleNames();
// Output: ["admin"] or ["manager"] etc

// Check what permissions they have  
$user->getAllPermissions()->pluck('name');

// Check specific feature access
$service = app(\App\Services\MenuService::class);
$service->canAccessFeature('asset.create', $user);
// true or false

$service->canAccessFeature('asset.delete', $user);
// true or false
```

---

## Common Implementation Pattern

This is the pattern used throughout your app:

```blade
@extends('layouts.admin.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1>Entity List</h1>
    
    {{-- CREATE BUTTON --}}
    @canAccessFeature('entity.create')
        <a href="{{ route('entity.create') }}" class="btn btn-primary">
            <i class="material-icons">add</i> Add New
        </a>
    @endcanAccessFeature
</div>

<div class="table-container">
    <table>
        <thead><tr>...</tr></thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>...</td>
                <td>
                    {{-- VIEW (always show) --}}
                    <a href="{{ route('entity.show', $item) }}">View</a>

                    {{-- EDIT --}}
                    @canAccessFeature('entity.edit')
                        <a href="{{ route('entity.edit', $item) }}">Edit</a>
                    @endcanAccessFeature

                    {{-- DELETE --}}
                    @canAccessFeature('entity.delete')
                        <form method="POST" action="{{ route('entity.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    @endcanAccessFeature
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

---

## What Routes Are Protected

✅ = Route has middleware added

**Assets:**
- ✅ GET /assets/create
- ✅ POST /assets (store)
- ✅ GET /assets/{asset}/edit
- ✅ PUT /assets/{asset} (update)
- ✅ POST /assets/import

**Users:**
- ✅ GET /users/create
- ✅ POST /users (store)
- ✅ GET /users/{user}/edit
- ✅ PUT /users/{user} (update)
- ✅ DELETE /users/{user}

**Departments:**
- ✅ GET /departments/create
- ✅ POST /departments (store)
- ✅ GET /departments/{department}/edit
- ✅ PUT /departments/{department} (update)
- ✅ DELETE /departments/{department}

**Locations:**
- ✅ GET /locations/create
- ✅ POST /locations (store)
- ✅ GET /locations/{location}/edit
- ✅ PUT /locations/{location} (update)
- ✅ DELETE /locations/{location}

**Categories:**
- ✅ GET /categories/create
- ✅ POST /categories (store)
- ✅ GET /categories/{category}/edit
- ✅ PUT /categories/{category} (update)
- ✅ DELETE /categories/{category}

**Maintenance:**
- ✅ GET /maintenance/create
- ✅ POST /maintenance (store)
- ✅ GET /maintenance/{maintenance}/edit
- ✅ PUT /maintenance/{maintenance} (update)
- ✅ POST /maintenance/{maintenance}/assign

---

## Security: Both View AND Route Checks

⚠️ **Important:** Don't rely ONLY on view checks!

```
┌─────────────────────────────────────────┐
│  Request to /assets/create              │
├─────────────────────────────────────────┤
│  1. Route Middleware (check.feature)     │ ← Security layer
│     Blocks unauthorized access          │
│                                          │
│  2. View Blade Directive (@canAccess)   │ ← UX layer
│     Hides buttons from unauthorized     │
└─────────────────────────────────────────┘
```

Both layers protect your application:
- **View layer**: Better UX (hide buttons they can't use)
- **Route layer**: Actual security (prevent direct URL access)

---

## Next Steps

1. **Test the Asset view** - Create/Edit/Delete buttons should now respect permissions
2. **Review CRUD_PERMISSION_IMPLEMENTATION.md** - For complete examples for all entities
3. **Update other views** - Follow the same pattern for Users, Departments, etc.
4. **Test with different users:**
   ```bash
   php artisan tinker
   $adminUser = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
   $staffUser = User::whereHas('roles', fn($q) => $q->where('name', 'staff'))->first();
   ```
5. **Clear cache if needed:**
   ```bash
   php artisan cache:clear
   ```

---

## Summary

✅ Permissions are now:
- **Enforced in routes** with middleware
- **Hidden in views** with Blade directives  
- **Configured in config/menus.php** for easy management
- **Role-based** through Spatie Permission package

Users without permissions will:
1. Not see the buttons/links in Views
2. Get 403 error if they try direct URL access (via middleware)

This provides secure, user-friendly permission control!
