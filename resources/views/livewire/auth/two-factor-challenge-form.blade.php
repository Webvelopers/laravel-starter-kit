@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
@endphp

<form wire:submit="authenticate" class="mt-8 space-y-4">
    <x-auth.field :label="__('frontend.auth.two_factor.code')" error="code">
        <x-auth.input-text
            name="code"
            wire:model.live.debounce.400ms="code"
            inputmode="numeric"
            autocomplete="one-time-code"
        />
    </x-auth.field>

    <x-auth.field :label="__('frontend.auth.two_factor.recovery_code')" error="recovery_code">
        <x-auth.input-text name="recovery_code" wire:model.live.debounce.400ms="recoveryCode" />
    </x-auth.field>

    <x-auth.button type="submit">{{ __('frontend.auth.two_factor.submit') }}</x-auth.button>
</form>
