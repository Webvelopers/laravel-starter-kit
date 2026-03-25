@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
@endphp

<form wire:submit="confirmPassword" class="mt-8 space-y-4">
    <x-auth.field :label="__('frontend.auth.password')" error="password">
        <x-auth.input-password
            name="password"
            wire:model.live.debounce.400ms="password"
            required
            autocomplete="current-password"
        />
    </x-auth.field>

    <x-auth.button type="submit">{{ __('frontend.auth.confirm_password.submit') }}</x-auth.button>
</form>
