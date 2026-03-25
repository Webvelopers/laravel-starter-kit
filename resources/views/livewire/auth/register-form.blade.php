@php
    $strengthWrapClass = 'surface-card';
    $strengthTrackClass = 'h-2 rounded-full bg-stone-200';
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
    $strengthTextClass = ($frontendTemplate ?? 'default') === 'shadcn' ? 'text-sm font-medium text-slate-700' : 'text-sm font-semibold text-stone-700';
    $strengthMutedClass = ($frontendTemplate ?? 'default') === 'shadcn' ? 'text-slate-500' : 'text-stone-500';
    $humanVerificationLabelClass = 'form-field';
    $humanVerificationPanelClass = ($frontendTemplate ?? 'default') === 'shadcn' ? 'rounded-2xl border border-slate-200 bg-slate-50 p-4' : 'rounded-2xl border border-stone-200 bg-stone-50 p-4';
    $humanVerificationHintClass = ($frontendTemplate ?? 'default') === 'shadcn' ? 'text-sm leading-6 text-slate-600' : 'text-sm leading-6 text-stone-600';
@endphp

<form wire:submit="register" class="auth-form">
    <x-auth.field :label="__('frontend.auth.register.name')" error="name">
        <x-auth.input-name name="name" wire:model.live.debounce.400ms="name" required autofocus />
    </x-auth.field>

    <x-auth.field :label="__('frontend.auth.email')" error="email">
        <x-auth.input-email name="email" wire:model.live.debounce.400ms="email" required />
    </x-auth.field>

    <x-auth.field :label="__('frontend.auth.password')" error="password">
        <x-auth.input-password-toggle
            name="password"
            wire:model.live.debounce.400ms="password"
            required
            autocomplete="new-password"
            :visible="$showPassword"
            toggle-action="togglePasswordVisibility"
            :show-label="__('frontend.auth.password_toggle.show')"
            :hide-label="__('frontend.auth.password_toggle.hide')"
        />
    </x-auth.field>

    <div class="{{ $strengthWrapClass }} space-y-3 p-4">
        <div class="flex items-center justify-between gap-3">
            <p class="{{ $strengthTextClass }}">
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
                <p class="{{ $passed ? 'text-emerald-700' : $strengthMutedClass }}">
                    {{ $passed ? 'OK' : '...' }} {{ __('frontend.password_strength.' . $check) }}
                </p>
            @endforeach
        </div>
    </div>

    <x-auth.field :label="__('frontend.auth.register.confirm_password')" error="passwordConfirmation">
        <x-auth.input-password-toggle
            name="password_confirmation"
            wire:model.live.debounce.400ms="passwordConfirmation"
            required
            autocomplete="new-password"
            :visible="$showPasswordConfirmation"
            toggle-action="togglePasswordConfirmationVisibility"
            :show-label="__('frontend.auth.password_toggle.show')"
            :hide-label="__('frontend.auth.password_toggle.hide')"
        />
    </x-auth.field>

    @if ($registrationHumanVerificationEnabled && filled($humanVerificationImage))
        <div class="space-y-2">
            <p class="{{ $humanVerificationLabelClass }}">{{ __('frontend.auth.register.human_verification') }}</p>
            <div class="{{ $humanVerificationPanelClass }}">
                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <img
                        src="{{ $humanVerificationImage }}"
                        alt="{{ __('frontend.auth.register.human_verification_image_alt') }}"
                        class="h-20 w-[220px] rounded-2xl border border-slate-200 bg-white object-cover"
                    />
                    <div class="space-y-3">
                        <p class="{{ $humanVerificationHintClass }}">
                            {{ __('frontend.auth.register.human_verification_hint') }}
                        </p>
                        <x-auth.button-secondary type="button" wire:click="refreshHumanVerification">
                            {{ __('frontend.auth.register.refresh_human_verification') }}
                        </x-auth.button-secondary>
                    </div>
                </div>
                <x-auth.input-text
                    name="human_verification_answer"
                    wire:model.live.debounce.400ms="humanVerificationAnswer"
                    inputmode="text"
                    autocomplete="off"
                    class="mt-3"
                />
            </div>
            <x-input-error :messages="$errors->get('human_verification_answer')" />
        </div>
    @endif

    <div class="form-actions">
        <a href="{{ route('login') }}" class="text-link">
            {{ __('frontend.auth.register.have_account') }}
        </a>
        <x-auth.button type="submit">{{ __('frontend.auth.register.submit') }}</x-auth.button>
    </div>
</form>
