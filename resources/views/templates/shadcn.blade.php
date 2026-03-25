<section class="landing-shell">
    <div class="landing-hero-panel">
        <div class="landing-hero-content">
            <div class="landing-badge">
                <span class="landing-badge-dot"></span>
                {{ __('frontend.templates.shadcn_badge') }}
            </div>
            <h2 class="hero-title">
                {{ __('frontend.templates.shadcn_headline') }}
            </h2>
            <p class="hero-copy">
                {{ __('frontend.templates.shadcn_body') }}
            </p>

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
                <a href="https://ui.shadcn.com" target="_blank" rel="noreferrer" class="ghost-button">
                    {{ __('frontend.templates.shadcn_docs') }}
                </a>
            </div>
        </div>
    </div>

    <div class="landing-grid">
        <div class="landing-metrics-card">
            <div class="landing-metrics-header">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        {{ __('frontend.templates.shadcn_metrics_label') }}
                    </p>
                    <h3 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">
                        {{ __('frontend.templates.shadcn_metrics_title') }}
                    </h3>
                </div>
                <span class="landing-status-pill">
                    {{ __('frontend.templates.shadcn_ship_ready') }}
                </span>
            </div>

            <div class="landing-metrics-grid">
                <div class="landing-stat-card">
                    <p class="text-xs font-medium tracking-[0.25em] text-slate-500 uppercase">
                        {{ __('frontend.templates.shadcn_stat_one_label') }}
                    </p>
                    <p class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">12</p>
                    <p class="mt-2 text-sm text-slate-600">{{ __('frontend.templates.shadcn_stat_one_copy') }}</p>
                </div>
                <div class="landing-stat-card">
                    <p class="text-xs font-medium tracking-[0.25em] text-slate-500 uppercase">
                        {{ __('frontend.templates.shadcn_stat_two_label') }}
                    </p>
                    <p class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">3</p>
                    <p class="mt-2 text-sm text-slate-600">{{ __('frontend.templates.shadcn_stat_two_copy') }}</p>
                </div>
                <div class="landing-stat-card">
                    <p class="text-xs font-medium tracking-[0.25em] text-slate-500 uppercase">
                        {{ __('frontend.templates.shadcn_stat_three_label') }}
                    </p>
                    <p class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">2</p>
                    <p class="mt-2 text-sm text-slate-600">{{ __('frontend.templates.shadcn_stat_three_copy') }}</p>
                </div>
            </div>
        </div>

        <div class="landing-side-stack">
            <div class="landing-side-card">
                <div class="landing-side-card-header">
                    <h3 class="text-lg font-semibold tracking-tight text-slate-950">
                        {{ __('frontend.templates.shadcn_panel_title') }}
                    </h3>
                    <span class="landing-panel-pill">
                        {{ __('frontend.templates.shadcn_panel_status') }}
                    </span>
                </div>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ __('frontend.templates.shadcn_panel_body') }}</p>
                <div class="landing-feature-list">
                    <div class="landing-feature-card">
                        <p class="text-sm font-medium text-slate-900">
                            {{ __('frontend.templates.shadcn_feature_one_title') }}
                        </p>
                        <p class="mt-1 text-sm leading-6 text-slate-600">
                            {{ __('frontend.templates.shadcn_feature_one_body') }}
                        </p>
                    </div>
                    <div class="landing-feature-card">
                        <p class="text-sm font-medium text-slate-900">
                            {{ __('frontend.templates.shadcn_feature_two_title') }}
                        </p>
                        <p class="mt-1 text-sm leading-6 text-slate-600">
                            {{ __('frontend.templates.shadcn_feature_two_body') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="landing-terminal-card">
                <p class="text-sm font-medium text-slate-300">{{ __('frontend.templates.shadcn_terminal_label') }}</p>
                <div class="landing-terminal-window">
                    <p>$ composer run dev</p>
                    <p>$ ./vendor/bin/pest tests/Feature/HomeTest.php</p>
                    <p>$ npm run build</p>
                </div>
            </div>
        </div>
    </div>
</section>
