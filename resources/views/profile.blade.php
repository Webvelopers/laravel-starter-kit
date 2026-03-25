<x-layouts.app :title="__('frontend.profile.title')">
    @php
        $user = auth()->user();
        $role = $currentUserRole ?? \App\Enums\UserRole::User;
        $twoFactorEnabled = $user->two_factor_secret !== null;
        $twoFactorConfirmed = $user->two_factor_confirmed_at !== null;
    @endphp

    <section class="profile-section">
        <div class="profile-header">
            <p class="page-eyebrow">
                {{ __('frontend.profile.title') }}
            </p>
            <h1 class="page-title">{{ __('frontend.profile.headline') }}</h1>
            <p class="page-copy">{{ __('frontend.profile.description') }}</p>
            <p class="role-badge">{{ __('frontend.roles.label') }}: {{ __('frontend.roles.' . $role->value) }}</p>
        </div>

        <x-status-banner :status="session('status')" />

        <div class="profile-grid">
            @livewire('profile.personal-section')
            @livewire('profile.security-section')
        </div>
    </section>
</x-layouts.app>
