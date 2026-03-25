<x-layouts.app :title="__('frontend.auth.login.title')">
    <section class="auth-card">
        <p class="auth-eyebrow">
            {{ __('frontend.auth.login.eyebrow') }}
        </p>
        <h1 class="auth-title">{{ __('frontend.auth.login.headline') }}</h1>
        <p class="auth-copy">{{ __('frontend.auth.login.description') }}</p>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <x-auth-session-status :status="session('status')" />

            <x-auth.field :label="__('frontend.auth.email')" error="email">
                <x-auth.input-email id="email" name="email" value="{{ old('email') }}" required autofocus />
            </x-auth.field>

            <x-auth.field :label="__('frontend.auth.password')" error="password">
                <x-auth.input-password id="password" name="password" required autocomplete="current-password" />
            </x-auth.field>

            <x-auth.checkbox id="remember" name="remember">{{ __('frontend.auth.remember') }}</x-auth.checkbox>

            <div class="form-actions">
                <a href="{{ route('password.request') }}" class="text-link">
                    {{ __('frontend.auth.login.forgot') }}
                </a>
                <x-auth.button type="submit">{{ __('frontend.auth.login.submit') }}</x-auth.button>
            </div>
        </form>
    </section>
</x-layouts.app>
