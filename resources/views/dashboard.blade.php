@php
    $user = auth()->user();
    $role = $currentUserRole ?? \App\Enums\UserRole::User;
@endphp

<x-layouts.app :title="__('frontend.dashboard.eyebrow')">
    <section class="dashboard-section">
        <div class="dashboard-hero">
            <p class="page-eyebrow">
                {{ __('frontend.dashboard.eyebrow') }}
            </p>
            <h1 class="page-title">
                {{ __('frontend.dashboard.greeting', ['name' => $user->name]) }}
            </h1>
            <p class="page-copy">{{ __('frontend.dashboard.description') }}</p>
            <p class="role-badge">{{ __('frontend.roles.label') }}: {{ __('frontend.roles.' . $role->value) }}</p>

            <div class="dashboard-actions">
                <a href="{{ route('profile') }}" class="primary-button">
                    {{ __('frontend.dashboard.manage_profile') }}
                </a>
                @if ($role->isAdmin())
                    <a href="{{ route('admin.settings') }}" class="secondary-button">
                        {{ __('frontend.dashboard.admin_settings') }}
                    </a>
                @endif

                <a href="https://laravel.com/docs/12.x" target="_blank" rel="noreferrer" class="secondary-button">
                    {{ __('frontend.dashboard.docs') }}
                </a>
            </div>
        </div>

        <livewire:starter-checklist />
    </section>
</x-layouts.app>
