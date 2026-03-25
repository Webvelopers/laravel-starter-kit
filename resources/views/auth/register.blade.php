@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $cardClass = $isShadcn ? 'mx-auto max-w-xl rounded-[1.5rem] border border-slate-200 bg-white p-8 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'mx-auto max-w-xl rounded-[2rem] border border-stone-200 bg-white/90 p-8 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $eyebrowClass = $isShadcn ? 'text-xs font-medium tracking-[0.28em] text-slate-500 uppercase' : 'text-sm font-semibold tracking-[0.3em] text-amber-700 uppercase';
    $titleClass = $isShadcn ? 'mt-3 text-4xl font-semibold tracking-tight text-slate-950' : 'font-display mt-3 text-4xl text-stone-950';
    $copyClass = $isShadcn ? 'mt-3 text-base leading-7 text-slate-600' : 'mt-3 text-base leading-7 text-stone-600';
    $labelClass = $isShadcn ? 'block space-y-2 text-sm font-medium text-slate-700' : 'block space-y-2 text-sm font-medium text-stone-700';
    $inputClass = $isShadcn ? 'w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10' : 'w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-900 transition outline-none focus:border-stone-900 focus:bg-white';
    $linkClass = $isShadcn ? 'text-sm font-medium text-slate-700 underline underline-offset-4' : 'text-sm font-semibold text-stone-700 underline underline-offset-4';
    $buttonClass = $isShadcn ? 'rounded-md bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800' : 'rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-amber-50 transition hover:bg-stone-700';
@endphp

<x-layouts.app :title="__('frontend.auth.register.title')">
    <section class="{{ $cardClass }}">
        <p class="{{ $eyebrowClass }}">
            {{ __('frontend.auth.register.eyebrow') }}
        </p>
        <h1 class="{{ $titleClass }}">{{ __('frontend.auth.register.headline') }}</h1>
        <p class="{{ $copyClass }}">{{ __('frontend.auth.register.description') }}</p>

        @livewire('auth.register-form')
    </section>
</x-layouts.app>
