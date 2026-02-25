# Integration Guide - Adding Permission Checks to Existing Routes

This guide shows how to add permission checks to your existing routes and controllers.

## Option 1: Protect Routes with Middleware

### Simple Menu-Based Protection
```php
// routes/web.php
Route::get('/master-data/assets', [AssetController::class, 'index'])
    ->middleware('check.menu:asset_management');
```

### Feature-Based Protection  
```php
Route::post('/master-data/assets', [AssetController::class, 'store'])
    ->middleware('check.feature:asset.create');

Route::delete('/master-data/assets/{asset}', [AssetController::class, 'destroy'])
    ->middleware('check.feature:asset.delete');
```

### Using Spatie Permission
```php
Route::get('/master-data/assets', [AssetController::class, 'index'])
    ->middleware('permission:view_asset');
```

### Combined Protection
```php
Route::middleware(['auth', 'check.menu:asset_management'])->group(function () {
    Route::get('/assets', [AssetController::class, 'index']);
    Route::get('/assets/create', [AssetController::class, 'create']);
    Route::post('/assets', [AssetController::class, 'store'])
        ->middleware('check.feature:asset.create');
    
    Route::get('/assets/{asset}/edit', [AssetController::class, 'edit']);
    Route::put('/assets/{asset}', [AssetController::class, 'update'])
        ->middleware('check.feature:asset.edit');
    
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])
        ->middleware('check.feature:asset.delete');
});
```

## Option 2: Check Permissions in Controller

### In Controller Method
```php
namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\MenuService;

class AssetController extends Controller
{
    private MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function store(Request $request)
    {
        // Check feature access
        if (!$this->menuService->canAccessFeature('asset.create')) {
            abort(403, 'Anda tidak memiliki izin membuat aset.');
        }

        // Create asset logic
    }

    public function update(Request $request, Asset $asset)
    {
        // Check permission
        if (!auth()->user()->hasPermissionTo('edit_asset')) {
            abort(403, 'Anda tidak memiliki izin mengedit aset.');
        }

        // Update asset logic
    }

    public function destroy(Asset $asset)
    {
        // Check role-based access
        if (!auth()->user()->hasAnyRole(['admin', 'direktur'])) {
            abort(403, 'Hanya admin dan direktur yang dapat menghapus aset.');
        }

        // Delete logic
    }
}
```

## Option 3: Hide/Show UI Elements in Views

### Basic Usage
```blade
<div class="actions">
    <!-- Only show Edit button if user has permission -->
    @if(auth()->user()->hasPermissionTo('edit_asset'))
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
            Edit
        </a>
    @endif

    <!-- Only show Delete button for admins -->
    @if(auth()->user()->hasRole('admin'))
        <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Sure?')">Delete</button>
        </form>
    @endif
</div>
```

### Using Blade Directives
```blade
<!-- Check menu access -->
@canViewMenu('asset_management')
    <li><a href="{{ route('assets.index') }}">Manajemen Asset</a></li>
@endcanViewMenu

<!-- Check feature access -->
@canAccessFeature('asset.create')
    <button class="btn btn-primary" onclick="location.href='{{ route('assets.create') }}'">
        Tambah Asset Baru
    </button>
@endcanAccessFeature

<!-- Check role -->
@hasRole('admin')
    <div class="admin-tools">
        <!-- Admin-only tools -->
    </div>
@endhasRole

<!-- Check permission -->
@hasPermission('delete_asset')
    <button onclick="deleteCurrentAsset()">Delete</button>
@endhasPermission
```

## Option 4: Use Helper Class

### In Controller
```php
use App\Helpers\PermissionHelper;

class UserController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!PermissionHelper::isAdmin(auth()->user())) {
            abort(403);
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function assignRole(Request $request, User $user)
    {
        // Check if current user can assign roles (admin only)
        if (!PermissionHelper::isAdmin(auth()->user())) {
            abort(403);
        }

        PermissionHelper::assignRole($user, $request->role, clearExisting: true);
        
        return back()->with('success', 'Role assigned successfully');
    }
}
```

### In View
```blade
@php
    use App\Helpers\PermissionHelper;
    $isDirector = PermissionHelper::isDirector(auth()->user());
@endphp

@if($isDirector)
    <!-- Director-only content -->
    <div class="director-panel">
        <!-- Content here -->
    </div>
@endif
```

## Step-by-Step Example: Complete Asset CRUD

```php
// routes/web.php
Route::middleware(['auth'])->prefix('master-data')->group(function () {
    // Asset Management - Protected by menu
    Route::prefix('assets')->middleware('check.menu:asset_management')->group(function () {
        // List assets - only view permission required
        Route::get('/', [AssetController::class, 'index'])
            ->name('assets.index');

        // Create asset - need create permission
        Route::get('create', [AssetController::class, 'create'])
            ->middleware('check.feature:asset.create')
            ->name('assets.create');
        
        Route::post('/', [AssetController::class, 'store'])
            ->middleware('check.feature:asset.create')
            ->name('assets.store');

        // Edit asset - need edit permission
        Route::get('{asset}/edit', [AssetController::class, 'edit'])
            ->middleware('check.feature:asset.edit')
            ->name('assets.edit');
        
        Route::put('{asset}', [AssetController::class, 'update'])
            ->middleware('check.feature:asset.edit')
            ->name('assets.update');

        // Delete asset - restricted to specific roles
        Route::delete('{asset}', [AssetController::class, 'destroy'])
            ->middleware('check.feature:asset.delete')
            ->name('assets.destroy');

        // View asset
        Route::get('{asset}', [AssetController::class, 'show'])
            ->name('assets.show');

        // Import route - admin only
        Route::get('import', [AssetController::class, 'import'])
            ->middleware('role:admin')
            ->name('assets.import');
    });
});
```

```php
// app/Http/Controllers/AssetController.php
namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\MenuService;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function __construct(private MenuService $menuService)
    {
    }

    public function index()
    {
        // Additional check (middleware should handle this, but double-check)
        if (!$this->menuService->canViewMenu('asset_management')) {
            abort(403);
        }

        $assets = Asset::paginate(15);
        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        // Middleware checks this, but we can also check in controller
        if (!$this->menuService->canAccessFeature('asset.create')) {
            abort(403);
        }

        $categories = Category::all();
        $departments = Department::all();
        $locations = Location::all();

        return view('admin.assets.create', compact('categories', 'departments', 'locations'));
    }

    public function store(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'asset_tag' => 'required|unique:assets',
            'name' => 'required',
            // ...
        ]);

        // Create
        $asset = Asset::create($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil dibuat');
    }

    public function edit(Asset $asset)
    {
        if (!$this->menuService->canAccessFeature('asset.edit')) {
            abort(403);
        }

        $categories = Category::all();
        $departments = Department::all();
        $locations = Location::all();

        return view('admin.assets.edit', compact('asset', 'categories', 'departments', 'locations'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required',
            // ...
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil diperbarui');
    }

    public function destroy(Asset $asset)
    {
        // Only admin and direktur can delete
        if (!auth()->user()->hasAnyRole(['admin', 'direktur'])) {
            abort(403, 'Anda tidak memiliki izin menghapus aset.');
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil dihapus');
    }
}
```

```blade
<!-- resources/views/admin/assets/index.blade.php -->
@extends('layouts.admin.app')

@section('content')
<div class="assets-container">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Manajemen Aset</h1>
        
        <!-- Create button - only show if user has permission -->
        @canAccessFeature('asset.create')
            <a href="{{ route('assets.create') }}" class="btn btn-primary">
                + Tambah Aset
            </a>
        @endcanAccessFeature
    </div>

    @if($assets->count())
        <table class="w-full">
            <thead>
                <tr>
                    <th>Kode Aset</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Departemen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->asset_tag }}</td>
                        <td>{{ $asset->name }}</td>
                        <td>{{ $asset->category->name }}</td>
                        <td>{{ $asset->department->name }}</td>
                        <td>{{ $asset->condition }}</td>
                        <td>
                            <!-- Always show view -->
                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-info">
                                View
                            </a>

                            <!-- Show edit only if permitted -->
                            @canAccessFeature('asset.edit')
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            @endcanAccessFeature

                            <!-- Show delete only for restricted roles -->
                            @hasRole(['admin', 'direktur'])
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus?')">
                                        Delete
                                    </button>
                                </form>
                            @endhasRole
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $assets->links() }}
    @else
        <p class="text-gray-500">Tidak ada aset ditemukan.</p>
    @endif
</div>
@endsection
```

## Testing Permission Integration

```bash
# In Tinker
php artisan tinker

# Get a user
$user = User::find(1);

# Check what they can see
app(\App\Services\MenuService::class)->getAccessibleMenus($user);

# Check specific access
app(\App\Services\MenuService::class)->canViewMenu('asset_management', $user);
app(\App\Services\MenuService::class)->canAccessFeature('asset.create', $user);

# Assign role
$user->assignRole('manager');

# Now recheck
app(\App\Services\MenuService::class)->getAccessibleMenus();
```

## Common Patterns

### Pattern 1: Admin-Only Routes
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('settings', SettingController::class);
    Route::resource('logs', LogController::class);
});
```

### Pattern 2: Feature-Based Restrictions
```php
Route::middleware(['auth'])->group(function () {
    Route::post('/assets/import', [AssetController::class, 'import'])
        ->middleware('check.feature:asset.import');
});
```

### Pattern 3: Conditional Buttons in View
```blade
<div class="btn-group">
    <a href="{{ route('assets.show', $asset) }}" class="btn">View</a>
    
    @canAccessFeature('asset.edit')
        <a href="{{ route('assets.edit', $asset) }}" class="btn">Edit</a>
    @endcanAccessFeature
    
    @canAccessFeature('asset.delete')
        <button onclick="deleteAsset({{ $asset->id }})">Delete</button>
    @endcanAccessFeature
</div>
```

### Pattern 4: Audit Trail for Sensitive Operations
```php
public function destroy(Asset $asset)
{
    if (!auth()->user()->hasAnyRole(['admin', 'direktur'])) {
        abort(403);
    }

    // Log the action
    \Log::info("Asset deleted by " . auth()->user()->name, [
        'asset_id' => $asset->id,
        'user_id' => auth()->id(),
        'timestamp' => now(),
    ]);

    $asset->delete();
}
```

## Next Steps

1. Choose your preferred protection method (middleware, controller checks, or view directives)
2. Start by protecting your most sensitive routes
3. Update views to hide/show features based on permissions
4. Test thoroughly with different user roles
5. Monitor logs for unauthorized access attempts
