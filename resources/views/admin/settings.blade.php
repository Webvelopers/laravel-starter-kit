@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $pageTitleClass = $isShadcn ? 'text-4xl font-semibold tracking-tight text-slate-950' : 'font-display text-4xl text-stone-950';
    $eyebrowClass = $isShadcn ? 'text-xs font-medium tracking-[0.28em] text-slate-500 uppercase' : 'text-sm font-semibold tracking-[0.35em] text-amber-700 uppercase';
    $bodyCopyClass = $isShadcn ? 'max-w-2xl text-base leading-8 text-slate-600' : 'max-w-2xl text-base leading-8 text-stone-600';
    $sectionCardClass = $isShadcn ? 'rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'rounded-[2rem] border border-stone-200 bg-white/90 p-6 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $fieldLabelClass = $isShadcn ? 'block space-y-2 text-sm font-medium text-slate-700' : 'block space-y-2 text-sm font-medium text-stone-700';
    $sectionTitleClass = $isShadcn ? 'text-2xl font-semibold tracking-tight text-slate-950' : 'font-display text-2xl text-stone-950';
    $sectionDescriptionClass = $isShadcn ? 'mt-2 text-sm leading-7 text-slate-500' : 'mt-2 text-sm leading-7 text-stone-500';
    $tableWrapClass = $isShadcn ? 'mt-6 overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white' : 'mt-6 overflow-hidden rounded-[1.5rem] border border-stone-200 bg-white';
    $tableHeaderClass = $isShadcn ? 'bg-slate-50 text-left text-xs font-medium tracking-[0.18em] text-slate-500 uppercase' : 'bg-stone-50 text-left text-xs font-semibold tracking-[0.22em] text-stone-500 uppercase';
    $tableCellClass = $isShadcn ? 'border-t border-slate-200 px-4 py-4 text-sm text-slate-700' : 'border-t border-stone-200 px-4 py-4 text-sm text-stone-700';
    $capabilityCardClass = $isShadcn ? 'rounded-[1.25rem] border border-slate-200 bg-slate-50 p-5' : 'rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5';
    $badgeClass = $isShadcn ? 'inline-flex rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium tracking-[0.18em] text-slate-600 uppercase' : 'inline-flex rounded-full border border-stone-200 bg-white px-3 py-1 text-xs font-semibold tracking-[0.22em] text-stone-700 uppercase';
    $checkboxWrapClass = $isShadcn ? 'flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-3' : 'flex items-start gap-3 rounded-2xl border border-stone-200 bg-white p-3';
    $inputClass = $isShadcn ? 'w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10' : 'w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-900 transition outline-none focus:border-stone-900 focus:bg-white';
    $primaryButtonClass = $isShadcn ? 'rounded-md bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800' : 'rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-amber-50 transition hover:bg-stone-700';
    $secondaryButtonClass = $isShadcn ? 'rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50' : 'rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-900 hover:bg-white';
@endphp

<x-layouts.app :title="__('frontend.admin.title')">
    <section class="space-y-8">
        <div class="space-y-2">
            <p class="{{ $eyebrowClass }}">{{ __('frontend.admin.eyebrow') }}</p>
            <h1 class="{{ $pageTitleClass }}">{{ __('frontend.admin.headline') }}</h1>
            <p class="{{ $bodyCopyClass }}">{{ __('frontend.admin.description') }}</p>
        </div>

        <x-status-banner :status="session('status')" />

        @livewire('admin.human-verification-section')
        @livewire('admin.role-capabilities-section')
        @livewire('admin.user-roles-section')
    </section>
</x-layouts.app>
