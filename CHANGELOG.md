# Changelog

All notable changes to this project are documented in this file.

The format follows Keep a Changelog and the project uses Semantic Versioning as a practical reference for Laravel Starter Kit evolution.

## [Unreleased]

### Added

- Typed role system with `admin` and `user` profiles.
- Role middleware and protected administration settings area.
- Typed application setting keys for global configuration access.
- Dedicated model for frontend preferences outside the `User` model.
- Additional seeders for administration and standard user role validation.
- Admin-facing settings screen for registration safeguards.
- Feature and unit coverage for roles, access control, and seeders.

### Changed

- Refactored route closures into dedicated controllers and request objects.
- Moved frontend template persistence out of `users` into its own model/table.
- Restored `User` closer to Laravel defaults while keeping focused authorization helpers.
- Simplified Blade composition for template options and cleaned duplicated view layers.
- Improved project documentation to reflect actual architecture and workflows.

### Removed

- Obsolete migration for `frontend_template` in `users`.
- Empty `AppServiceProvider` registration.
- Redundant Starter Kit feature test file and unused template wrapper component.
- Unused frontend dependencies and bootstrap script.

### Fixed

- Tightened access control for global human verification settings.
- Restored missing status feedback for profile/admin actions.
- Cleaned static analysis issues and dead conditional view logic.

## [0.0.1] - 2026-01-01

### Added

- Initial Laravel Starter Kit structure.
- Base project configuration.
- Core authentication scaffolding.
