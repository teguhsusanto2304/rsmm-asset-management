# RSMM Asset Management - Copilot Instructions

## Project Overview
**RSMM Asset Management** is a Laravel 12 asset tracking system with role-based access control, department hierarchy, and comprehensive asset lifecycle management. Uses SQLite by default with Blade templating for UI, Tailwind CSS for styling, and Spatie Laravel Permission for role/permission management.

## Architecture Overview

### Core Components
- **Models** (`app/Models/`): Asset, User, Category, Department, Location, Role
- **Controllers** (`app/Http/Controllers/`): Authentication, CRUD operations for each entity
- **Database**: SQLite by default (`database/database.sqlite`); all schema via migrations
- **Frontend**: Blade views with Tailwind CSS + Vite bundling

### Key Data Flow
1. **Authentication**: Session-based using `AuthController` → validates credentials → stores session in database
2. **Authorization**: Spatie Permission guards routes; check user roles before accessing assets
3. **Assets Management**: Assets belong to Categories, Departments, Locations; relationships defined in migrations

## Critical Setup & Development Workflow

### Initial Setup (Critical - Must Do First)
```bash
# 1. Generate APP_KEY (required for encryption)
php artisan key:generate

# 2. Create database file
touch database/database.sqlite

# 3. Run migrations
php artisan migrate

# 4. Seed admin user
php artisan db:seed AdminUserSeederCustom
```

### Development Commands
```bash
# Full dev environment (PHP server, queue listener, logs, Vite)
composer run dev

# Alternative: Run components separately
php artisan serve              # Artisan web server
npm run dev                    # Vite hot reload
php artisan queue:listen       # Background jobs
php artisan pail              # Stream logs

# Testing
php phpunit                   # Run tests
```

### Database Configuration
- **Default**: SQLite at `database/database.sqlite` (dev mode)
- **Sessions**: Uses database driver (`SESSION_DRIVER=database`)
- **Encryption**: Requires `APP_KEY` in `.env` (see setup above)
- **Foreign Keys**: Enabled by default in SQLite config

## Project-Specific Patterns & Conventions

### Model Patterns
- **Asset Model**: Uses UUID primary keys (`HasUuids` trait), soft deletes
  - Related to: Category, Department, Location, User (assigned_to)
  - Key fields: barcode, asset_tag, serial_number, condition, warranty dates
- **User Model**: Uses `HasRoles` trait from Spatie Permission
  - Extra fields: department, role, status, avatar (non-standard)
  
### Migration Order Matters
Follow chronological order:
1. `0001_*_create_users_table` → `add_user_management_fields` (roles/departments)
2. `create_permission_tables` → `create_departments_table` → `create_locations_table` → `create_categories_table`
3. `create_asset_table` (depends on all above)

### Form Validation
- Use `Validator::make()` directly in controllers (not form requests)
- Custom validation messages in Indonesian (see `AuthController::login()`)
- Always return `->withInput()` on validation failures

### Blade View Conventions
- Session-stored in database; check `SESSION_DRIVER=database` in `.env`
- Flash messages via `session()->flash('message', 'text')`
- Components in `resources/views/components/`

## Integration Points & External Dependencies

### Core Dependencies
- **Laravel 12**: Framework core
- **Spatie Laravel Permission**: Role-based access control (installed, configured in `config/permission.php`)
- **Simple QR Code** (`simplesoftwareio/simple-qrcode`): Asset barcode generation
- **Laravel CSP**: Content Security Policy headers

### Frontend Stack
- **Tailwind CSS 4** + **Vite 6**: Built via `resources/js/app.js`, `resources/css/app.css`
- **Axios**: AJAX requests (available in `resources/js/bootstrap.js`)

## Common Troubleshooting for AI Agents

### Database File Missing Error
```
ERROR: Database file at path [...\database\database.sqlite] does not exist
```
→ Run: `php artisan migrate` (creates `.sqlite` file automatically if missing)

### Missing Encryption Key
```
ERROR: No application encryption key has been specified
```
→ Run: `php artisan key:generate` (generates `APP_KEY` in `.env`)

### Session/Database Errors on First Load
→ Ensure migrations ran: `php artisan migrate --fresh --seed`

## File Organization Reference
```
app/Models/               # Domain models with relationships
app/Http/Controllers/     # Single-responsibility controllers
database/migrations/      # Schema changes (ordered by timestamp)
resources/views/          # Blade templates
routes/web.php           # All routes (auth, admin, public)
config/                  # Laravel + package configuration
```

## Testing Approach
- Unit tests: `tests/Unit/`
- Feature tests: `tests/Feature/`
- Run: `php phpunit` or `vendor/bin/phpunit`
