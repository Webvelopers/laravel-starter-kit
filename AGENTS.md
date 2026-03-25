# AGENTS.md

This file guides coding agents working in this Laravel Starter Kit.

## Project Snapshot

- Stack: PHP 8.2+, Laravel 12, Fortify, Livewire 3, Blade, Tailwind CSS 4, Vite.
- Tests: Pest 3 on top of PHPUnit, with `RefreshDatabase` enabled for Feature tests.
- Static analysis and refactoring: PHPStan (Larastan) and Rector.
- Formatters: Laravel Pint for PHP, Prettier for JS/CSS/Blade/JSON/YAML.
- Default testing database: in-memory SQLite via `phpunit.xml`.
- Frontend entrypoints: `resources/css/app.css` and `resources/js/app.js`.

## Important Paths

- `app/Actions/Fortify` - auth-related action classes.
- `app/Http/Middleware/SetLocale.php` - locale resolution from session.
- `app/Livewire` - Livewire components.
- `app/Models/User.php` - user model with auth-related casts and hidden fields.
- `app/Providers/FortifyServiceProvider.php` - Fortify view bindings and rate limits.
- `resources/views` - Blade UI.
- `resources/css/app.css` - Tailwind v4 sources and theme variables.
- `routes/web.php` - web routes, closures, middleware groups.
- `tests/Feature` and `tests/Unit` - Pest tests.

## Install And Setup

Run the normal project bootstrap if dependencies are missing:

```bash
composer install
npm install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed
```

## Daily Commands

### Development

- `composer run dev` - starts Laravel server, queue listener, Pail, and Vite together.
- `php artisan serve` - backend only.
- `npm run dev` - Vite only.

### Build

- `npm run build` - production frontend build.

### Lint And Format

- `composer run lint` - runs Pint and frontend Prettier check.
- `composer run test:lint` - check-only lint pass (`pint --test` + Prettier check).
- `composer run pint` - format PHP.
- `./vendor/bin/pint --test` - check PHP formatting without rewriting.
- `npm run lint` - Prettier check for JS, CSS, HTML, Blade, JSON, YAML.
- `npm run lint:fix` - Prettier write mode.

### Static Analysis And Refactoring

- `composer run phpstan` - runs PHPStan/Larastan at max level on `app/`.
- `composer run refactor` - runs Rector and rewrites code.
- `composer run test:refactor` - Rector dry run.
- `composer run fix` - Rector, Pint, Prettier write, then PHPStan.

### Test Suite

- `composer run test` - full quality gate: Rector dry run, lint, PHPStan, Pest.
- `composer run test:unit` - Pest suite.
- `composer run pest` - Pest in parallel.
- `./vendor/bin/pest` - direct Pest execution.
- `php artisan test` - Laravel test runner if preferred.

## Running A Single Test

Prefer Pest directly for focused work.

- Single file: `./vendor/bin/pest tests/Feature/AuthFlowTest.php`
- Single test by name: `./vendor/bin/pest --filter="logs in verified users"`
- Single test in one file: `./vendor/bin/pest tests/Feature/AuthFlowTest.php --filter="logs out authenticated users"`
- Single Unit test file: `./vendor/bin/pest tests/Unit/UserModelTest.php`
- PHPUnit-style filter via Artisan: `php artisan test --filter="registers a user with a normalized email address"`

Notes:

- Feature tests use `RefreshDatabase`; expect database state to reset per test.
- Test env uses SQLite in memory, array cache/session, sync queue, and disabled Pulse/Telescope.
- Use `composer run pest` only when you want parallel execution across the suite.

## Style Rules From Tooling

The repository already enforces several PHP rules in `pint.json`:

- Start PHP files with `declare(strict_types=1);`.
- Prefer final classes where possible; existing app classes are commonly `final`.
- Require explicit visibility on class members.
- Import classes, constants, and functions instead of relying on global namespace lookups.
- Use strict comparisons.
- Prefer `private` over `protected` when inheritance is not needed.
- Keep class elements in the configured order: traits, constants, properties, constructor, magic/phpunit, then methods.
- Use lowercase keywords and lowercase static references.

Formatting from `.editorconfig` and existing files:

- Use spaces, not tabs.
- Default indentation is 4 spaces.
- YAML uses 2 spaces.
- End files with a newline.
- Trim trailing whitespace except in Markdown.

## PHP Conventions

- Follow Laravel style plus the stricter Pint rules above.
- Add scalar and return types whenever possible.
- Use precise PHPDoc for shaped arrays and collection-like structures when native types are not enough.
- Use imported class names, not fully-qualified inline types, except for occasional one-off return types already present in the codebase.
- Prefer small, focused action or service classes over large controllers.
- Keep route closures short; move growing logic into actions, middleware, or dedicated classes.
- Normalize user input close to validation or persistence boundaries, e.g. lowercasing emails before save.
- Prefer framework helpers that make intent obvious: `filled()`, `back()`, `now()`, `config()`, `route()`.

## Imports

- Group imports at the top after the namespace.
- Do not leave unused imports.
- Import Laravel facades and contracts explicitly.
- Alias imports only when a real naming conflict exists.

## Naming

- Classes: PascalCase (`CreateNewUser`, `SetLocale`).
- Methods and variables: camelCase (`integerConfig`, `$twoFactorConfirmed`).
- Test descriptions: sentence case strings passed to `it(...)` and written from user-visible behavior.
- Blade component names: kebab-case file names and `<x-...>` usage.
- Translation keys: dot notation grouped by feature, e.g. `frontend.profile.title`.

## Error Handling And Validation

- Prefer Laravel validation over manual conditional error handling for request-like input.
- Use named error bags when multiple forms share a page, as in the profile screen.
- Throw or allow framework exceptions to surface instead of swallowing them.
- For config-driven values, guard and cast explicitly when the source can be mixed.
- Favor safe defaults for invalid state, e.g. fallback strings or default config values.
- Keep rate limiting, auth flow, and security rules explicit and close to the relevant provider/action.

## Blade, Livewire, And Frontend Conventions

- Preserve the existing Blade-first architecture; do not introduce a SPA framework casually.
- Keep visual language consistent with the current warm palette, `DM Sans` / `Space Grotesk` typography, and rounded card-heavy layout unless the task intentionally redesigns it.
- Prefer translation strings over hard-coded user-facing copy.
- Keep Tailwind utility ordering compatible with Prettier + `prettier-plugin-tailwindcss`.
- Use `@vite([...])` for asset entrypoints.
- Put reusable UI into Blade components or Livewire components when duplication appears.
- Keep inline PHP in Blade minimal; if logic grows, move it into a component, view model, or class.

## Testing Conventions

- Write new tests in Pest style unless a file clearly uses PHPUnit class syntax.
- Feature tests should cover user behavior and redirects, auth state, validation, and rendered content where relevant.
- Unit tests should stay narrow and avoid unnecessary framework bootstrapping.
- Reuse factories and Laravel helpers for setup.
- Assert meaningful outcomes, not only status codes.

## Agent Workflow Expectations

- Before editing, inspect existing patterns in nearby files and follow them.
- Prefer the composer scripts when they already encode the intended workflow.
- After PHP changes, usually run at least targeted Pest + `./vendor/bin/pint --test`.
- After frontend changes, usually run `npm run lint` and `npm run build` if assets changed materially.
- If you change behavior, add or update tests whenever practical.
- Do not commit generated debug artifacts from `storage/debugbar`.

## Repository-Specific Observations

- No existing `AGENTS.md` was present at the repository root when this file was created.
- No repo-local Cursor rules were found in `.cursor/rules/` or `.cursorrules`.
- No Copilot instructions file was found at `.github/copilot-instructions.md`.
- If any of those files are added later, update this document so agents follow them.

## Good First Read Order For Agents

1. `composer.json`
2. `package.json`
3. `routes/web.php`
4. `app/Providers/FortifyServiceProvider.php`
5. relevant files under `app/Actions/Fortify`
6. matching Blade views in `resources/views`
7. related Pest files in `tests/`
