@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $sectionCardClass = $isShadcn ? 'rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'rounded-[2rem] border border-stone-200 bg-white/90 p-6 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $subtlePanelClass = $isShadcn ? 'mt-8 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-5' : 'mt-8 space-y-4 rounded-2xl border border-stone-200 bg-stone-50 p-5';
    $fieldLabelClass = $isShadcn ? 'block space-y-2 text-sm font-medium text-slate-700' : 'block space-y-2 text-sm font-medium text-stone-700';
    $sectionTitleClass = $isShadcn ? 'text-2xl font-semibold tracking-tight text-slate-950' : 'font-display text-2xl text-stone-950';
    $sectionDescriptionClass = $isShadcn ? 'mt-2 text-sm leading-7 text-slate-500' : 'mt-2 text-sm leading-7 text-stone-500';
    $twoFactorTitleClass = $isShadcn ? 'font-medium text-slate-900' : 'font-semibold text-stone-900';
    $twoFactorBodyClass = $isShadcn ? 'mt-1 text-sm text-slate-500' : 'mt-1 text-sm text-stone-500';
    $twoFactorCardClass = $isShadcn ? 'rounded-2xl border border-slate-200 bg-white p-4' : 'rounded-2xl border border-stone-200 bg-white p-4';
    $twoFactorCardTitleClass = $isShadcn ? 'text-sm font-medium text-slate-700' : 'text-sm font-medium text-stone-700';
    $qrWrapClass = $isShadcn ? 'mt-4 inline-flex rounded-xl bg-slate-950 p-4 text-white' : 'mt-4 inline-flex rounded-2xl bg-stone-900 p-4 text-white';
    $twoFactorEnableButtonClass = $isShadcn ? 'rounded-md bg-slate-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800' : 'rounded-full bg-stone-900 px-4 py-2 text-sm font-semibold text-amber-50 transition hover:bg-stone-700';
    $recoveryCodeClass = $isShadcn ? 'rounded-md bg-white px-3 py-2 text-sm text-slate-700' : 'rounded-xl bg-white px-3 py-2 text-sm text-stone-700';
    $recoveryActionClass = $isShadcn ? 'text-sm font-medium text-emerald-800 underline underline-offset-4' : 'text-sm font-semibold text-emerald-800 underline underline-offset-4';
    $inputClass = $isShadcn ? 'w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10' : 'w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-900 transition outline-none focus:border-stone-900 focus:bg-white';
    $secondaryButtonClass = $isShadcn ? 'rounded-md border border-slate-300 px-5 py-3 text-sm font-medium text-slate-900 transition hover:bg-slate-50' : 'rounded-full border border-stone-900 px-5 py-3 text-sm font-semibold text-stone-900 transition hover:bg-stone-900 hover:text-amber-50';
    $dangerButtonClass = $isShadcn ? 'rounded-md border border-rose-200 px-4 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-50' : 'rounded-full border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-50';
    $strengthWrapClass = $isShadcn ? 'rounded-xl border border-slate-200 bg-white p-4' : 'rounded-2xl border border-stone-200 bg-white p-4';
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

<section class="{{ $sectionCardClass }}">
    <h2 class="{{ $sectionTitleClass }}">{{ __('frontend.profile.security_title') }}</h2>
    <p class="{{ $sectionDescriptionClass }}">{{ __('frontend.profile.security_description') }}</p>

    <form wire:submit="updatePassword" class="mt-6 space-y-4">
        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.current_password') }}</span>
            <input
                type="password"
                name="current_password"
                wire:model.live.debounce.400ms="currentPassword"
                required
                class="{{ $inputClass }}"
            />
        </label>
        <x-input-error :messages="$errors->get('current_password')" />

        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.new_password') }}</span>
            <input
                type="password"
                name="password"
                wire:model.live.debounce.400ms="password"
                required
                class="{{ $inputClass }}"
            />
        </label>
        <x-input-error :messages="$errors->get('password')" />

        <div class="{{ $strengthWrapClass }} space-y-3">
            <div class="flex items-center justify-between gap-3">
                <p
                    class="{{ $isShadcn ? 'text-sm font-medium text-slate-700' : 'text-sm font-semibold text-stone-700' }}"
                >
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

        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.confirm_password') }}</span>
            <input
                type="password"
                name="password_confirmation"
                wire:model.live.debounce.400ms="passwordConfirmation"
                required
                class="{{ $inputClass }}"
            />
        </label>

        <button type="submit" class="{{ $secondaryButtonClass }}">
            {{ __('frontend.profile.update_password') }}
        </button>
    </form>

    <div class="{{ $subtlePanelClass }}">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="{{ $twoFactorTitleClass }}">{{ __('frontend.profile.two_factor_title') }}</h3>
                <p class="{{ $twoFactorBodyClass }}">
                    {{ $twoFactorEnabled ? __('frontend.profile.two_factor_enabled') : __('frontend.profile.two_factor_disabled') }}
                </p>
            </div>

            @if ($twoFactorEnabled)
                <button type="button" wire:click="disableTwoFactor" class="{{ $dangerButtonClass }}">
                    {{ __('frontend.profile.disable') }}
                </button>
            @else
                <button type="button" wire:click="enableTwoFactor" class="{{ $twoFactorEnableButtonClass }}">
                    {{ __('frontend.profile.enable') }}
                </button>
            @endif
        </div>

        @if ($twoFactorEnabled && ! $twoFactorConfirmed)
            <div class="{{ $twoFactorCardClass }}">
                <p class="{{ $twoFactorCardTitleClass }}">{{ __('frontend.profile.scan_qr') }}</p>
                <div class="{{ $qrWrapClass }}">{!! $user?->twoFactorQrCodeSvg() !!}</div>
            </div>

            <form wire:submit="confirmTwoFactor" class="space-y-3">
                <label class="{{ $fieldLabelClass }}">
                    <span>{{ __('frontend.profile.confirmation_code') }}</span>
                    <input
                        type="text"
                        name="code"
                        wire:model.live.debounce.400ms="code"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="{{ $inputClass }}"
                    />
                </label>
                <x-input-error :messages="$errors->get('code')" />
                <button type="submit" class="{{ $secondaryButtonClass }}">
                    {{ __('frontend.profile.confirm_2fa') }}
                </button>
            </form>
        @endif

        @if ($twoFactorConfirmed)
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                <p class="text-sm font-medium text-emerald-800">{{ __('frontend.profile.recovery_codes') }}</p>
                <div class="mt-3 grid gap-2 md:grid-cols-2">
                    @foreach ($user?->recoveryCodes() ?? [] as $recoveryCode)
                        <code class="{{ $recoveryCodeClass }}">{{ $recoveryCode }}</code>
                    @endforeach
                </div>

                <button type="button" wire:click="regenerateRecoveryCodes" class="{{ $recoveryActionClass }} mt-4">
                    {{ __('frontend.profile.regenerate_codes') }}
                </button>
            </div>
        @endif
    </div>
</section>
