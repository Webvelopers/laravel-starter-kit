<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\AppSetting;
use App\Models\User;
use App\Models\UserRoleAssignment;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\AppSettingsSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\StandardUserSeeder;

use function Pest\Laravel\seed;

it('creates the starter admin user when the database seeder runs', function (): void {
    seed(DatabaseSeeder::class);

    $user = User::query()->where('email', 'starter@example.com')->first();

    expect($user)
        ->not->toBeNull()
        ->and($user?->name)->toBe('Starter Admin')
        ->and($user?->email_verified_at)->not->toBeNull()
        ->and(UserRoleAssignment::roleFor($user))->toBe(UserRole::Admin);
});

it('seeds human verification as enabled by default', function (): void {
    seed(AppSettingsSeeder::class);

    expect(AppSetting::registrationHumanVerificationEnabled())->toBeTrue();
});

it('creates additional administration profiles', function (): void {
    seed(AdminUserSeeder::class);

    expect(UserRoleAssignment::query()->where('role', UserRole::Admin->value)->count())
        ->toBeGreaterThanOrEqual(2)
        ->and(User::query()->where('email', 'ops-admin@example.com')->exists())->toBeTrue();
});

it('creates standard user profiles for role validation', function (): void {
    seed(StandardUserSeeder::class);

    expect(UserRoleAssignment::query()->where('role', UserRole::User->value)->count())
        ->toBeGreaterThanOrEqual(5)
        ->and(User::query()->where('email', 'member@example.com')->exists())->toBeTrue()
        ->and(User::query()->where('email', 'analyst@example.com')->exists())->toBeTrue();
});
