@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $strengthWrapClass = $isShadcn ? 'rounded-xl border border-slate-200 bg-slate-50 p-4' : 'rounded-2xl border border-stone-200 bg-stone-50 p-4';
    $strengthTrackClass = $isShadcn ? 'h-2 rounded-full bg-slate-200' : 'h-2 rounded-full bg-stone-200';
    $strengthFillClass = match (true) {
        $passwordStrength['score'] <= 1 => 'bg-rose-500',
        $passwordStrength['score'] <= 3 => 'bg-amber-500',
        default => 'bg-emerald-500',
    };
    $strengthLabelClass = match (true) {
        $passwordStrength['score'] <= 1 => 'text-rose-600',
        $passwordStrength['score'] <= 3 => 'text-amber-600',
        default => 'text-emerald-600',
    };
@endphp

<form wire:submit="resetPassword" class="mt-8 space-y-4">
    <x-auth.field :label="__('frontend.auth.email')" error="email">
        <x-auth.input-email name="email" wire:model.live.debounce.400ms="email" required autofocus />
    </x-auth.field>

    <x-auth.field :label="__('frontend.auth.password')" error="password">
        <x-auth.input-password
            name="password"
            wire:model.live.debounce.400ms="password"
            required
            autocomplete="new-password"
        />
    </x-auth.field>

    <div class="{{ $strengthWrapClass }} space-y-3">
        <div class="flex items-center justify-between gap-3">
            <p class="{{ $isShadcn ? 'text-sm font-medium text-slate-700' : 'text-sm font-semibold text-stone-700' }}">
                {{ __('frontend.password_strength.title') }}
            </p>
            <span class="{{ $strengthLabelClass }} text-sm font-semibold">{{ $passwordStrength['label'] }}</span>
        </div>

        <div class="{{ $strengthTrackClass }} overflow-hidden">
            <div
                class="{{ $strengthFillClass }} h-full rounded-full transition-all duration-300"
                @style(['width: ' . $passwordStrength['percentage'] . '%'])
            ></div>
        </div>

        <div class="grid gap-2 text-sm md:grid-cols-2">
            @foreach ($passwordStrength['checks'] as $check => $passed)
                <p class="{{ $passed ? 'text-emerald-700' : ($isShadcn ? 'text-slate-500' : 'text-stone-500') }}">
                    {{ $passed ? 'OK' : '...' }} {{ __('frontend.password_strength.' . $check) }}
                </p>
            @endforeach
        </div>
    </div>

    <x-auth.field :label="__('frontend.auth.reset_password.confirm_password')" error="passwordConfirmation">
        <x-auth.input-password
            name="password_confirmation"
            wire:model.live.debounce.400ms="passwordConfirmation"
            required
            autocomplete="new-password"
        />
    </x-auth.field>

    <x-auth.button type="submit">{{ __('frontend.auth.reset_password.submit') }}</x-auth.button>
</form>
