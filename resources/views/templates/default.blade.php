<section class="landing-grid">
    <div class="space-y-6">
        <p class="hero-eyebrow">
            {{ __('frontend.welcome.title') }}
        </p>
        <h1 class="hero-title">
            {{ __('frontend.welcome.headline') }}
        </h1>
        <p class="hero-copy">{{ __('frontend.welcome.description') }}</p>

        <div class="landing-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="primary-button">
                    {{ __('frontend.welcome.open_dashboard') }}
                </a>
            @else
                <a href="{{ route('register') }}" class="primary-button">
                    {{ __('frontend.welcome.create_account') }}
                </a>
                <a href="{{ route('login') }}" class="secondary-button">
                    {{ __('frontend.welcome.login') }}
                </a>
            @endauth
            <a href="https://laravel.com/docs/12.x" target="_blank" rel="noreferrer" class="ghost-button">
                {{ __('frontend.welcome.docs') }}
            </a>
        </div>
    </div>

    <div class="landing-feature-panel">
        <p class="landing-feature-eyebrow">
            {{ __('frontend.welcome.includes') }}
        </p>
        <div class="landing-feature-list">
            <div class="landing-feature-card">
                <h2 class="landing-feature-title">{{ __('frontend.welcome.cards.auth_title') }}</h2>
                <p class="landing-feature-copy">{{ __('frontend.welcome.cards.auth_body') }}</p>
            </div>
            <div class="landing-feature-card">
                <h2 class="landing-feature-title">{{ __('frontend.welcome.cards.dx_title') }}</h2>
                <p class="landing-feature-copy">{{ __('frontend.welcome.cards.dx_body') }}</p>
            </div>
            <div class="landing-feature-card">
                <h2 class="landing-feature-title">{{ __('frontend.welcome.cards.ui_title') }}</h2>
                <p class="landing-feature-copy">{{ __('frontend.welcome.cards.ui_body') }}</p>
            </div>
        </div>
    </div>
</section>
