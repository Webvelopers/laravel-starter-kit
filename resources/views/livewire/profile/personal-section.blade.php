@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $sectionCardClass = $isShadcn ? 'rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'rounded-[2rem] border border-stone-200 bg-white/90 p-6 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $subtleCardClass = $isShadcn ? 'rounded-2xl border border-slate-200 bg-slate-50 p-4' : 'rounded-2xl border border-stone-200 bg-stone-50 p-4';
    $fieldLabelClass = $isShadcn ? 'block space-y-2 text-sm font-medium text-slate-700' : 'block space-y-2 text-sm font-medium text-stone-700';
    $sectionTitleClass = $isShadcn ? 'text-2xl font-semibold tracking-tight text-slate-950' : 'font-display text-2xl text-stone-950';
    $sectionDescriptionClass = $isShadcn ? 'mt-2 text-sm leading-7 text-slate-500' : 'mt-2 text-sm leading-7 text-stone-500';
    $warningCardClass = $isShadcn ? 'rounded-xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800' : 'rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800';
    $inputClass = $isShadcn ? 'w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10' : 'w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-900 transition outline-none focus:border-stone-900 focus:bg-white';
    $primaryButtonClass = $isShadcn ? 'rounded-md bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800' : 'rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-amber-50 transition hover:bg-stone-700';
    $secondaryButtonClass = $isShadcn ? 'rounded-md border border-slate-300 px-5 py-3 text-sm font-medium text-slate-900 transition hover:bg-slate-50' : 'rounded-full border border-stone-900 px-5 py-3 text-sm font-semibold text-stone-900 transition hover:bg-stone-900 hover:text-amber-50';
@endphp

<section class="{{ $sectionCardClass }}">
    <h2 class="{{ $sectionTitleClass }}">{{ __('frontend.profile.personal_title') }}</h2>
    <p class="{{ $sectionDescriptionClass }}">{{ __('frontend.profile.personal_description') }}</p>

    <form wire:submit="updateLocale" class="{{ $subtleCardClass }} mt-6 space-y-3">
        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.language') }}</span>
            <select name="locale" wire:model.live="locale" class="{{ $inputClass }}">
                @foreach ($supportedLocales as $supportedLocale)
                    <option value="{{ $supportedLocale }}">
                        {{ __('frontend.profile.languages.' . $supportedLocale) }}
                    </option>
                @endforeach
            </select>
        </label>

        <x-input-error :messages="$errors->get('locale')" />

        <button type="submit" class="{{ $secondaryButtonClass }}">
            {{ __('frontend.profile.update_language') }}
        </button>
    </form>

    <form wire:submit="updateTemplate" class="{{ $subtleCardClass }} mt-6 space-y-3">
        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.template') }}</span>
            <select name="frontend_template" wire:model.live="frontendTemplate" class="{{ $inputClass }}">
                @foreach ($supportedTemplates as $supportedTemplate)
                    <option value="{{ $supportedTemplate }}">
                        {{ __('frontend.profile.templates.' . $supportedTemplate) }}
                    </option>
                @endforeach
            </select>
        </label>

        <x-input-error :messages="$errors->get('frontendTemplate')" />

        <p class="{{ $isShadcn ? 'text-sm leading-7 text-slate-500' : 'text-sm leading-7 text-stone-500' }}">
            {{ __('frontend.profile.template_description') }}
        </p>

        <button type="submit" class="{{ $primaryButtonClass }}">
            {{ __('frontend.profile.update_template') }}
        </button>
    </form>

    @if ($currentUserRole->isAdmin())
        <div class="{{ $subtleCardClass }} mt-6 space-y-3">
            <p class="{{ $fieldLabelClass }}">{{ __('frontend.profile.admin_tools') }}</p>
            <p class="{{ $isShadcn ? 'text-sm leading-7 text-slate-500' : 'text-sm leading-7 text-stone-500' }}">
                {{ __('frontend.profile.admin_tools_description') }}
            </p>
            <a href="{{ route('admin.settings') }}" class="{{ $secondaryButtonClass }} inline-flex">
                {{ __('frontend.profile.open_admin_settings') }}
            </a>
        </div>
    @endif

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-4">
        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.name') }}</span>
            <input type="text" name="name" wire:model.live.debounce.400ms="name" required class="{{ $inputClass }}" />
        </label>
        <x-input-error :messages="$errors->get('name')" />

        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.profile.email') }}</span>
            <input
                type="email"
                name="email"
                wire:model.live.debounce.400ms="email"
                required
                class="{{ $inputClass }}"
            />
        </label>
        <x-input-error :messages="$errors->get('email')" />

        @if (! auth()->user()?->hasVerifiedEmail())
            <div class="{{ $warningCardClass }}">
                {{ __('frontend.profile.email_unverified') }}
                <button
                    type="button"
                    wire:click="resendVerification"
                    class="mt-3 font-semibold underline underline-offset-4"
                >
                    {{ __('frontend.profile.resend_verification') }}
                </button>
            </div>
        @endif

        <button type="submit" class="{{ $primaryButtonClass }}">
            {{ __('frontend.profile.save') }}
        </button>
    </form>
</section>
