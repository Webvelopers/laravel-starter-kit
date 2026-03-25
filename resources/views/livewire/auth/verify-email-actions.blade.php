@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $buttonClass = $isShadcn ? 'rounded-md bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800' : 'rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-amber-50 transition hover:bg-stone-700';
    $linkClass = $isShadcn ? 'text-sm font-medium text-slate-700 underline underline-offset-4' : 'text-sm font-semibold text-stone-700 underline underline-offset-4';
@endphp

<div class="mt-8 space-y-4">
    <x-auth-session-status
        :status="session('status') === 'verification-link-sent' ? __('frontend.auth.verify_email.resent') : null"
    />

    <button type="button" wire:click="resend" class="{{ $buttonClass }}">
        {{ __('frontend.auth.verify_email.submit') }}
    </button>

    <button type="button" wire:click="logout" class="{{ $linkClass }}">
        {{ __('frontend.auth.verify_email.logout') }}
    </button>
</div>
