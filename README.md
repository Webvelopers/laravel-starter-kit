# Laravel Starter Template

An opinionated Laravel starter for shipping new products faster. It includes a complete authentication flow, typed roles and capabilities, user-facing frontend preferences, a small admin area, and a quality toolchain that is ready for day-to-day development.

## Highlights

- Laravel 12 on PHP 8.2+
- Fortify-based authentication without Jetstream
- Livewire 3, Blade, Tailwind CSS 4, and Vite
- Pest, PHPStan, Rector, Pint, and Prettier preconfigured
- English and Spanish localization
- Typed `admin` and `user` roles
- Role capability management from the admin panel
- Per-user locale and frontend template preferences
- Configurable human verification during registration

## Included Features

- Public landing page at `/`
- Template gallery at `/templates` with preview pages
- Authenticated dashboard for verified users
- Account profile with:
    - locale switching
    - frontend template selection
    - name and email updates
    - password updates
    - two-factor authentication
- Admin settings page at `/admin/settings`
- Global registration safeguards managed by administrators
- Seeded demo users for local validation

## Tech Stack

### Backend

- `app/Actions/Fortify` contains registration, profile, and password actions
- `app/Enums/UserRole.php` defines the system roles
- `app/Enums/AppSettingKey.php` centralizes typed application setting keys
- `app/Http/Controllers` keeps route handlers thin and focused
- `app/Http/Requests` encapsulates request validation
- `app/Http/Middleware/EnsureUserHasRole.php` protects role-based routes
- `app/Http/Middleware/SetLocale.php` resolves shared locale and frontend state
- `app/Models/AppSetting.php` exposes typed access to global settings
- `app/Support/RoleCapabilityMatrix.php` manages configurable role capabilities

### Frontend

- Blade-first architecture without unnecessary SPA complexity
- Main application shell in `resources/views/components/layouts/app.blade.php`
- Custom auth screens in `resources/views/auth`
- Dashboard in `resources/views/dashboard.blade.php`
- Profile screen in `resources/views/profile.blade.php`
- Admin settings in `resources/views/admin/settings.blade.php`
- Template gallery and previews in `resources/views/templates`

### Data and Seeders

- `database/seeders/AdminUserSeeder.php` creates admin accounts
- `database/seeders/StandardUserSeeder.php` creates standard user accounts
- `database/seeders/DatabaseSeeder.php` runs the starter seed set

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite enabled in PHP

## Quick Start

```bash
cp .env.example .env
composer install
npm install
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed
```

Start the local development environment:

```bash
composer run dev
```

This script starts the Laravel server, queue listener, log tailing, and Vite together.

## Demo Users

Use these accounts after seeding the database.

### Administrators

- `starter@example.com` / `password`
- `ops-admin@example.com` / `password`

### Standard Users

- `member@example.com` / `password`
- `analyst@example.com` / `password`

Additional fake users are also seeded with the `user` role.

## Roles and Access Rules

The starter uses typed roles backed by `App\Enums\UserRole`.

- `admin`: full access to administrative settings and protected role management
- `user`: standard access to the authenticated application area

Current route rules:

- only authenticated and verified users can access `/dashboard` and `/profile`
- only authenticated and verified admins can access `/admin/settings`
- only admins can change the global registration safeguard setting
- only admins can update configurable capabilities for a role

## Global Settings

The application currently persists these starter-level settings:

- `registration_human_verification_enabled`
- `role_capabilities`

These settings are consumed through typed accessors and affect:

- registration form rendering
- registration validation
- admin configuration screens
- effective role permissions across the app

## Useful Commands

### Development

```bash
composer run dev
php artisan serve
npm run dev
```

### Database

```bash
php artisan migrate
composer run migrate
php artisan db:seed
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=StandardUserSeeder
```

### Linting and Static Analysis

```bash
./vendor/bin/pint --test
npm run lint
composer run phpstan
composer run test:refactor
```

### Tests

```bash
./vendor/bin/pest
composer run pest
composer run test
```

## Running Focused Tests

```bash
./vendor/bin/pest tests/Feature/AdminAccessTest.php
./vendor/bin/pest tests/Feature/DatabaseSeederTest.php
./vendor/bin/pest --filter="global human verification"
```

## Project Conventions

- Keep `User` close to Laravel defaults and move extra behavior into dedicated classes
- Store frontend preferences outside the `User` model when they are not identity-related
- Prefer `FormRequest` classes for HTTP validation
- Use enums for roles and typed keys for global settings
- Version lockfiles and keep the quality toolchain reproducible

## Recommended Read Order

If you are new to the codebase, start here:

1. `composer.json`
2. `package.json`
3. `routes/web.php`
4. `app/Providers/FortifyServiceProvider.php`
5. `app/Actions/Fortify`
6. `resources/views`
7. `tests`

## Recommended Verification Before Shipping Changes

```bash
./vendor/bin/pint --test
npm run lint
composer run phpstan
./vendor/bin/pest
npm run build
```

## Changelog

See `CHANGELOG.md` for the functional history of the starter.

## License

This project is released under the MIT license unless your derived product adopts a different license.
