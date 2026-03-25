<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Livewire\Admin\RoleCapabilitiesSection;
use App\Livewire\Admin\UserRolesSection;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use App\Livewire\Profile\PersonalSection;
use App\Livewire\Profile\SecuritySection;
use App\Models\AppSetting;
use App\Models\User;
use App\Models\UserFrontendPreference;
use App\Models\UserRoleAssignment;
use App\Support\RoleCapabilityMatrix;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('authenticates verified users through the livewire login form', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'password' => Hash::make('Password123!'),
        'email_verified_at' => now(),
    ]);

    Livewire::test(LoginForm::class)
        ->set('email', $user->email)
        ->set('password', 'Password123!')
        ->call('authenticate')
        ->assertRedirect('/dashboard');

    expect(Auth::id())->toBe($user->id);
});

it('shows a login error instead of crashing when credentials are invalid', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'password' => Hash::make('Password123!'),
        'email_verified_at' => now(),
    ]);

    Livewire::test(LoginForm::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('authenticate')
        ->assertHasErrors(['email']);

    expect(Auth::check())->toBeFalse();
});

it('registers a user through the livewire registration form with human verification enabled', function (): void {
    AppSetting::setRegistrationHumanVerificationEnabled(true);

    $component = Livewire::test(RegisterForm::class);

    $answer = session()->get('registration_human_verification.answer');

    $component
        ->set('name', 'Livewire Human')
        ->set('email', 'livewire-human@example.com')
        ->set('password', 'T3mplate!Safe#987')
        ->set('passwordConfirmation', 'T3mplate!Safe#987')
        ->set('humanVerificationAnswer', is_string($answer) ? $answer : '')
        ->call('register')
        ->assertRedirect('/dashboard');

    expect(User::query()->where('email', 'livewire-human@example.com')->exists())->toBeTrue();
});

it('validates login email format while typing', function (): void {
    Livewire::test(LoginForm::class)
        ->set('email', 'not-an-email')
        ->assertHasErrors(['email' => 'email'])
        ->set('email', 'valid@example.com')
        ->assertHasNoErrors('email');
});

it('validates registration password confirmation before submit', function (): void {
    AppSetting::setRegistrationHumanVerificationEnabled(false);

    Livewire::test(RegisterForm::class)
        ->set('password', 'T3mplate!Safe#987')
        ->set('passwordConfirmation', 'different-password')
        ->assertHasErrors(['passwordConfirmation' => 'same'])
        ->set('passwordConfirmation', 'T3mplate!Safe#987')
        ->assertHasNoErrors('passwordConfirmation');
});

it('shows password strength guidance while typing a password', function (): void {
    AppSetting::setRegistrationHumanVerificationEnabled(false);

    Livewire::test(RegisterForm::class)
        ->assertSee(__('frontend.password_strength.empty'))
        ->set('password', 'weak')
        ->assertSee(__('frontend.password_strength.weak'))
        ->set('password', 'T3mplate!Safe#987')
        ->assertSee(__('frontend.password_strength.strong'));
});

it('updates the frontend template through the livewire profile component', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    Livewire::test(PersonalSection::class)
        ->set('frontendTemplate', 'shadcn')
        ->call('updateTemplate')
        ->assertRedirect(route('profile'));

    expect(UserFrontendPreference::templateFor($user->fresh()))->toBe('shadcn');
});

it('validates profile email format while editing', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    Livewire::test(PersonalSection::class)
        ->set('email', 'invalid-email')
        ->assertHasErrors(['email' => 'email'])
        ->set('email', 'updated@example.com')
        ->assertHasNoErrors('email');
});

it('validates password confirmation in the security section before submit', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    Livewire::test(SecuritySection::class)
        ->set('password', 'T3mplate!Fresh#654')
        ->set('passwordConfirmation', 'bad-confirmation')
        ->assertHasErrors(['passwordConfirmation' => 'same'])
        ->set('passwordConfirmation', 'T3mplate!Fresh#654')
        ->assertHasNoErrors('passwordConfirmation');
});

it('updates role capabilities through the livewire admin component', function (): void {
    /** @var User $admin */
    $admin = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    UserRoleAssignment::assign($admin, UserRole::Admin);

    actingAs($admin);

    Livewire::test(RoleCapabilitiesSection::class)
        ->set('selectedCapabilities.user', [
            'dashboard.access',
            'profile.manage_own',
            'frontend.choose_template',
            'admin.manage_settings',
        ])
        ->call('updateRoleCapabilities', UserRole::User->value)
        ->assertRedirect(route('admin.settings'));

    expect(RoleCapabilityMatrix::capabilitiesFor(UserRole::User))
        ->toContain('admin.manage_settings')
        ->toContain('dashboard.access');
});

it('updates another user role through the livewire admin component', function (): void {
    /** @var User $admin */
    $admin = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    UserRoleAssignment::assign($admin, UserRole::Admin);

    /** @var User $member */
    $member = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    UserRoleAssignment::assign($member, UserRole::User);

    actingAs($admin);

    Livewire::test(UserRolesSection::class)
        ->set('roleSelections.'.$member->id, UserRole::Admin->value)
        ->call('updateRole', $member->id)
        ->assertRedirect(route('admin.settings'));

    expect(UserRoleAssignment::roleFor($member))->toBe(UserRole::Admin);
});
