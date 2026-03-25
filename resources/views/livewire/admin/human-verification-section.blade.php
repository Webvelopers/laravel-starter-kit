@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $sectionCardClass = $isShadcn ? 'rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'rounded-[2rem] border border-stone-200 bg-white/90 p-6 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $fieldLabelClass = $isShadcn ? 'block space-y-2 text-sm font-medium text-slate-700' : 'block space-y-2 text-sm font-medium text-stone-700';
    $sectionTitleClass = $isShadcn ? 'text-2xl font-semibold tracking-tight text-slate-950' : 'font-display text-2xl text-stone-950';
    $sectionDescriptionClass = $isShadcn ? 'mt-2 text-sm leading-7 text-slate-500' : 'mt-2 text-sm leading-7 text-stone-500';
    $inputClass = $isShadcn ? 'w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10' : 'w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-900 transition outline-none focus:border-stone-900 focus:bg-white';
    $primaryButtonClass = $isShadcn ? 'rounded-md bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800' : 'rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-amber-50 transition hover:bg-stone-700';
@endphp

<section class="{{ $sectionCardClass }}">
    <h2 class="{{ $sectionTitleClass }}">{{ __('frontend.admin.human_verification_title') }}</h2>
    <p class="{{ $sectionDescriptionClass }}">{{ __('frontend.admin.human_verification_description') }}</p>

    <form wire:submit="save" class="mt-6 space-y-4">
        <label class="{{ $fieldLabelClass }}">
            <span>{{ __('frontend.admin.human_verification_field') }}</span>
            <select wire:model="registrationHumanVerificationEnabled" class="{{ $inputClass }}">
                <option value="0">{{ __('frontend.profile.human_verification_options.disabled') }}</option>
                <option value="1">{{ __('frontend.profile.human_verification_options.enabled') }}</option>
            </select>
        </label>

        <x-input-error :messages="$errors->get('registrationHumanVerificationEnabled')" />

        <button type="submit" class="{{ $primaryButtonClass }}">
            {{ __('frontend.admin.save_settings') }}
        </button>
    </form>
</section>
