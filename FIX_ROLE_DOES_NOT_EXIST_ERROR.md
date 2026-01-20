# RoleDoesNotExist Error Fix - Summary

## Error
```
Spatie\Permission\Exceptions\RoleDoesNotExist
There is no role named `1` for guard `web`.
```

## Root Cause
The `syncRoles()` method from Spatie Permission was receiving numeric role IDs but internally was trying to look them up as role names, causing the error "There is no role named `1`".

## Solution Applied

### 1. UserController - store() Method
**Changed from:**
```php
$roleIds = array_filter(array_map('intval', $data['roles'] ?? []));
if (!empty($roleIds)) {
    $user->syncRoles($roleIds);
}
```

**Changed to:**
```php
$roles = Role::whereIn('id', array_filter(array_map('intval', $data['roles'] ?? [])))->get();
if ($roles->isNotEmpty()) {
    $user->syncRoles($roles);
}
```

### 2. UserController - update() Method
**Changed from:**
```php
$roleIds = array_filter(array_map('intval', $data['roles'] ?? []));
$user->syncRoles($roleIds);
```

**Changed to:**
```php
$roles = Role::whereIn('id', array_filter(array_map('intval', $data['roles'] ?? [])))->get();
$user->syncRoles($roles);
```

### 3. Role Selector Component - type casting
Enhanced the checkbox checked condition to ensure proper type comparison:
```blade
{{ in_array((int)$role->id, array_map('intval', $selectedRoles)) ? 'checked' : '' }}
```

## Why This Works
- Spatie's `syncRoles()` method expects **Role model instances**, not numeric IDs or role names
- By querying the Role models using `whereIn()` and passing the resulting collection, we ensure proper object handling
- This prevents Spatie from trying to treat numeric IDs as role names
- The method now properly updates the `model_has_roles` pivot table

## Files Modified
1. `app/Http/Controllers/UserController.php` - store() and update() methods
2. `resources/views/components/role-selector.blade.php` - Enhanced type checking

## Testing
- Clear cache: `php artisan cache:clear && php artisan config:clear`
- Refresh database: `php artisan migrate:refresh --seeder=AdminUserSeederCustom`
- Test creating a new user with roles
- Test editing a user and modifying role assignments
- Verify roles display correctly in user list

## Alternative Solutions Considered
1. Passing role IDs directly - **Failed** (causes name lookup error)
2. Using role names instead of IDs - **Not feasible** (form sends IDs from checkboxes)
3. Using `assignRole()` in a loop - **Works but inefficient**

## Spatie Permission Best Practices
- Always pass **Role model instances** to `syncRoles()`, `assignRole()`, etc.
- Use `whereIn('id', [...])` to fetch Role models before assignment
- Ensure validation rule uses `exists:roles,id` to verify IDs
- Guard name should be consistent ('web' in this case)
