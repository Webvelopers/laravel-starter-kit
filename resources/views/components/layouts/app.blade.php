@php
    $template = ($frontendTemplate ?? 'default') === 'shadcn' ? 'shadcn' : 'default';
    $templateCss = "resources/css/templates/{$template}.css";
    $resolvedBodyClass = trim('template-' . $template . ' ' . ($bodyClass ?? ''));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ $title ?? config('app.name') }}</title>
        <meta name="description" content="{{ __('frontend.meta.description') }}" />
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700|dm-sans:400,500,700"
            rel="stylesheet"
        />
        @stack('head')
        @vite(['resources/css/app.css', $templateCss, 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="{{ $resolvedBodyClass }}">
        <div class="app-shell">
            <header class="app-header">
                <a href="{{ route('home') }}" class="app-brand">
                    <span class="app-brand-badge">L12</span>
                    <span>{{ config('app.name') }}</span>
                </a>

                <nav class="app-nav">
                    <a href="{{ route('home') }}" class="app-nav-link">
                        {{ __('frontend.nav.home') }}
                    </a>
                    <a href="{{ route('templates.index') }}" class="app-nav-link">
                        {{ __('frontend.nav.templates') }}
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="app-nav-link">
                            {{ __('frontend.nav.dashboard') }}
                        </a>
                        @if (($currentUserRole ?? null)?->isAdmin())
                            <a href="{{ route('admin.settings') }}" class="app-nav-link">
                                {{ __('frontend.nav.admin') }}
                            </a>
                        @endif

                        <a href="{{ route('profile') }}" class="app-nav-link">
                            {{ __('frontend.nav.profile') }}
                        </a>

                        @livewire('shared.logout-button', ['label' => __('frontend.nav.logout'), 'class' => 'primary-button'], key('layout-logout'))
                    @else
                        <a href="{{ route('login') }}" class="app-nav-link">
                            {{ __('frontend.nav.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="primary-button">
                            {{ __('frontend.nav.register') }}
                        </a>
                    @endauth
                </nav>
            </header>

            <main class="app-main">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
