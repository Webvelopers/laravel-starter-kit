@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $linkClass = $isShadcn ? 'text-sm font-medium text-slate-700 underline underline-offset-4' : 'text-sm font-semibold text-stone-700 underline underline-offset-4';
@endphp

<form wire:submit="authenticate" class="mt-8 space-y-4">
    <x-auth-session-status :status="session('status')" />

    <x-auth.field :label="__('frontend.auth.email')" error="email">
        <x-auth.input-email id="email" name="email" wire:model.live="email" required autofocus />
    </x-auth.field>

    <x-auth.field :label="__('frontend.auth.password')" error="password">
        <x-auth.input-password
            id="password"
            name="password"
            wire:model.live="password"
            required
            autocomplete="current-password"
        />
    </x-auth.field>

    <x-auth.checkbox id="remember" name="remember" wire:model="remember">
        {{ __('frontend.auth.remember') }}
    </x-auth.checkbox>

    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
        <a href="{{ route('password.request') }}" class="{{ $linkClass }}">
            {{ __('frontend.auth.login.forgot') }}
        </a>
        <x-auth.button type="submit">{{ __('frontend.auth.login.submit') }}</x-auth.button>
    </div>
</form>
