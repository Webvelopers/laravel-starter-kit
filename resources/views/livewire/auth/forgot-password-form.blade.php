@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
@endphp

<form wire:submit="sendResetLink" class="mt-8 space-y-4">
    <x-auth-session-status :status="session('status')" />

    <x-auth.field :label="__('frontend.auth.email')" error="email">
        <x-auth.input-email name="email" wire:model.live.debounce.400ms="email" required autofocus />
    </x-auth.field>

    <x-auth.button type="submit">{{ __('frontend.auth.forgot_password.submit') }}</x-auth.button>
</form>
