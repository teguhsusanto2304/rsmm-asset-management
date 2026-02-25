# Implementation Checklist

Use this checklist to guide your implementation of the permission system.

## Phase 1: Initial Setup ✅ COMPLETED

- [x] Create `config/menus.php` configuration file
- [x] Create `app/Services/MenuService.php` service class
- [x] Create `app/Http/Middleware/CheckMenuAccess.php` middleware
- [x] Create `app/Http/Middleware/CheckFeatureAccess.php` middleware
- [x] Register middleware in `bootstrap/app.php`
- [x] Register Blade directives in `AppServiceProvider`
- [x] Update `database/seeders/RoleSeeder.php` with 6 roles
- [x] Update `database/seeders/PermissionSeeder.php` with role assignments
- [x] Create `app/Helpers/PermissionHelper.php` utility class
- [x] Update sidebar to use MenuService

## Phase 2: Database Setup (DO THIS ONCE)

- [ ] Run migrations: `php artisan migrate`
- [ ] Seed roles: `php artisan db:seed RoleSeeder`
- [ ] Seed permissions: `php artisan db:seed PermissionSeeder`
- [ ] Verify database tables:
  - [ ] `roles` table has 6 roles
  - [ ] `permissions` table has 20 permissions
  - [ ] `role_has_permissions` table populated

## Phase 3: Test Basic Functionality

- [ ] Clear cache: `php artisan cache:clear`
- [ ] Login as admin user
- [ ] Verify sidebar shows all menus
- [ ] Logout and login as another role
- [ ] Verify sidebar shows role-appropriate menus
- [ ] Test Blade directives in a view
- [ ] Verify middleware protection on a test route

## Phase 4: Customize Configuration

- [ ] Review `config/menus.php`
- [ ] Adjust menu items to match your needs
- [ ] Update role/permission requirements
- [ ] Add or remove menu items as needed
- [ ] Define features you want to control
- [ ] Test configuration changes

## Phase 5: Protect Existing Routes

- [ ] Identify sensitive routes
- [ ] Add middleware protection to critical operations:
  - [ ] User management routes
  - [ ] Asset CRUD operations
  - [ ] Department management
  - [ ] Location management
  - [ ] Category management
  - [ ] Permission/Role management
- [ ] Test each protected route with different user roles

## Phase 6: Update Views

- [ ] Audit all Blade templates
- [ ] Hide/show elements based on permissions:
  - [ ] Create buttons
  - [ ] Edit buttons
  - [ ] Delete buttons
  - [ ] Admin panels
  - [ ] Advanced features
- [ ] Use appropriate directives:
  - [ ] `@canViewMenu()` for menu items
  - [ ] `@canAccessFeature()` for features
  - [ ] `@hasRole()` for role-based content
  - [ ] `@hasPermission()` for permission-based content

## Phase 7: Add Permission Checks to Controllers

For sensitive operations:
- [ ] Check permissions in form submission handlers
- [ ] Validate user has required role before allowing delete
- [ ] Add authorization checks to store/update operations
- [ ] Log sensitive operations for audit trail

Examples:
```php
// In controller
if (!auth()->user()->hasPermissionTo('delete_asset')) {
    abort(403, 'Unauthorized');
}
```

## Phase 8: Testing

### Unit Tests
- [ ] Test MenuService methods
- [ ] Test PermissionHelper methods
- [ ] Test permission checks

### Feature Tests
- [ ] Test middleware protection
- [ ] Test role-based access
- [ ] Test permission-based access
- [ ] Test feature access control

### Manual Testing Scenarios
- [ ] Test as admin: should see all menus
- [ ] Test as direktur: should see most menus except admin-only
- [ ] Test as manager: should see department/asset menus
- [ ] Test as supervisor: should see view-only menus
- [ ] Test as staff: should only see personal menus
- [ ] Test as technician: should see maintenance menus
- [ ] Test accessing restricted route: should get 403
- [ ] Test hidden UI elements: should not appear

## Phase 9: Assign Roles to Users

- [ ] Review existing users
- [ ] Assign appropriate roles:
  - [ ] Assign 'admin' to system administrators
  - [ ] Assign 'direktur' to directors
  - [ ] Assign 'manager' to managers
  - [ ] Assign 'supervisor' to supervisors
  - [ ] Assign 'staff' to regular employees
  - [ ] Assign 'technician' to maintenance staff
- [ ] Test each user's access

### Via Admin Panel (Once implemented)
```bash
php artisan tinker
$user = User::find(1);
$user->assignRole('admin');
exit
```

## Phase 10: Documentation & Training

- [ ] Review documentation files:
  - [ ] `PERMISSION_SYSTEM_GUIDE.md` - Comprehensive guide
  - [ ] `PERMISSION_QUICK_REFERENCE.md` - Quick reference
  - [ ] `PERMISSION_INTEGRATION_GUIDE.md` - Integration examples
  - [ ] `MENU_CONFIG_EXAMPLES.md` - Configuration examples
- [ ] Create internal documentation for your team
- [ ] Train team on new permission system
- [ ] Document role hierarchy in your organization
- [ ] Create permission matrix for different user types

## Phase 11: Deployment Preparation

- [ ] Test in staging environment
- [ ] Verify all migrations run successfully
- [ ] Test all permission scenarios
- [ ] Update documentation with production URLs
- [ ] Create backup of production database
- [ ] Plan rollback strategy if needed

### Pre-Deployment Checklist
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run migrations
php artisan migrate

# Seed data
php artisan db:seed RoleSeeder
php artisan db:seed PermissionSeeder

# Verify
php artisan tinker
# Check: Role::count(), Permission::count()
```

## Phase 12: Post-Deployment

- [ ] Verify all menus visible to appropriate roles
- [ ] Test user access with real users
- [ ] Monitor logs for permission errors
- [ ] Gather feedback from users
- [ ] Make adjustments as needed
- [ ] Document any custom configurations made

## Phase 13: Advanced Features (Optional)

- [ ] Implement menu caching for performance
- [ ] Create admin panel for menu management
- [ ] Add permission audit logging
- [ ] Create role assignment UI
- [ ] Implement time-based permissions (expiring roles)
- [ ] Add department-based access control

## Quick Troubleshooting

### Issue: Menus not showing
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Verify user has role: Check `model_has_roles` table
- [ ] Check config: Review `config/menus.php`
- [ ] Test in Tinker: `app(\App\Services\MenuService::class)->getAccessibleMenus()`

### Issue: Permission denied (403)
- [ ] Check middleware: Verify correct middleware on route
- [ ] Check user role: Verify user has required role
- [ ] Check permissions: Verify role-permission mapping in database
- [ ] Check config: Verify menu/feature config

### Issue: Cached permissions not updating
- [ ] Clear all caches: `php artisan cache:clear`
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Restart queue: `php artisan queue:restart`

## Success Criteria ✅

Your implementation is successful when:

- ✅ All 6 roles are created
- ✅ All 20 permissions are created
- ✅ Permissions are properly assigned to roles
- ✅ Sidebar dynamically shows menus based on user role
- ✅ Blade directives work in all views
- ✅ Middleware properly protects sensitive routes
- ✅ Different users see different menus
- ✅ Buttons/features are hidden for unauthorized users
- ✅ 403 errors appear when accessing unauthorized routes
- ✅ All routes with sensitive operations are protected
- ✅ Users understand the permission system
- ✅ Admin can manage roles and permissions

## Next Steps After Implementation

1. **Monitor & Maintain**
   - Watch for 403 errors in logs
   - Adjust permissions as needs change
   - Audit role assignments regularly

2. **Optimize Performance**
   - Cache menu structures
   - Use Spatie's permission caching
   - Profile authorization checks under load

3. **Improve User Experience**
   - Create role management UI
   - Add user-friendly error messages
   - Provide clear permission descriptions

4. **Enhance Security**
   - Log all permission changes
   - Implement audit trails
   - Restrict sensitive operations to admin

5. **Scale & Extend**
   - Consider department-based access
   - Implement resource-level permissions
   - Add time-based permissions
   - Support multi-tenancy if needed

---

**Status**: Implementation Guide Ready
**Last Updated**: February 25, 2026
**Version**: 1.0
