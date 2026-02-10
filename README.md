# Laravel Template

A base template to start **Laravel 12** projects from scratch with **essential security hardening** and a **clean development setup** from day one.

> Goal: start every project with structure, security, and discipline — not “we’ll fix it later”.

---

## Tech Stack (Opinionated but sane defaults)

- PHP 8.2+
- Laravel 12
- Laravel Fortify (authentication)
- Livewire 3 (full-stack UI)
- Pest (testing)
- Larastan / PHPStan (static analysis)
- Laravel Pint (code style)
- Rector (automated refactoring)
- Laravel Sail (local development with Docker)

---

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 20+ (if frontend assets are used)
- Database Configured: SQLite
- Database Available: SQLite / MySQL / PostgreSQL / MariaDB

---

## Quick Installation

```bash
git clone https://github.com/Webvelopers/laravel-template.git my-app
cd my-app
composer install
npm install
```

## Get Started

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
composer run dev
```

## Fix Bugs

```bash
composer run fix
```

## Run Tests

```bash
composer run test
```
