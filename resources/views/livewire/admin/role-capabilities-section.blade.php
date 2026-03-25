@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $sectionCardClass = $isShadcn ? 'rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'rounded-[2rem] border border-stone-200 bg-white/90 p-6 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $sectionTitleClass = $isShadcn ? 'text-2xl font-semibold tracking-tight text-slate-950' : 'font-display text-2xl text-stone-950';
    $sectionDescriptionClass = $isShadcn ? 'mt-2 text-sm leading-7 text-slate-500' : 'mt-2 text-sm leading-7 text-stone-500';
    $capabilityCardClass = $isShadcn ? 'rounded-[1.25rem] border border-slate-200 bg-slate-50 p-5' : 'rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5';
    $badgeClass = $isShadcn ? 'inline-flex rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium tracking-[0.18em] text-slate-600 uppercase' : 'inline-flex rounded-full border border-stone-200 bg-white px-3 py-1 text-xs font-semibold tracking-[0.22em] text-stone-700 uppercase';
    $checkboxWrapClass = $isShadcn ? 'flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-3' : 'flex items-start gap-3 rounded-2xl border border-stone-200 bg-white p-3';
    $secondaryButtonClass = $isShadcn ? 'rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50' : 'rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-900 hover:bg-white';
@endphp

<section class="{{ $sectionCardClass }}">
    <h2 class="{{ $sectionTitleClass }}">{{ __('frontend.admin.roles_title') }}</h2>
    <p class="{{ $sectionDescriptionClass }}">{{ __('frontend.admin.roles_description') }}</p>

    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        @foreach ($availableRoles as $availableRole)
            <article
                class="{{ $capabilityCardClass }} space-y-4"
                wire:key="role-capability-{{ $availableRole['value'] }}"
            >
                <div class="flex items-center justify-between gap-4">
                    <h3
                        class="{{ $isShadcn ? 'text-lg font-semibold text-slate-950' : 'font-display text-xl text-stone-950' }}"
                    >
                        {{ $availableRole['label'] }}
                    </h3>
                    <span class="{{ $badgeClass }}">{{ $availableRole['value'] }}</span>
                </div>

                <form wire:submit="updateRoleCapabilities('{{ $availableRole['value'] }}')" class="space-y-4">
                    <div class="space-y-2">
                        <p class="{{ $sectionDescriptionClass }} mt-0">
                            {{ __('frontend.admin.capabilities_title') }}
                        </p>

                        <div class="space-y-3">
                            @foreach ($allCapabilities as $capability)
                                @php
                                    $protected = in_array($capability, $availableRole['protected_capabilities'], true);
                                @endphp

                                <label class="{{ $checkboxWrapClass }}">
                                    <input
                                        type="checkbox"
                                        wire:model="selectedCapabilities.{{ $availableRole['value'] }}"
                                        value="{{ $capability }}"
                                        @disabled($protected)
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-950 focus:ring-slate-950"
                                    />
                                    <span class="space-y-1">
                                        <span
                                            class="{{ $isShadcn ? 'text-sm font-medium text-slate-900' : 'text-sm font-semibold text-stone-900' }} block"
                                        >
                                            {{ __('frontend.admin.capabilities.' . $capability . '.title') }}
                                        </span>
                                        <span
                                            class="{{ $isShadcn ? 'text-xs leading-6 text-slate-500' : 'text-xs leading-6 text-stone-500' }} block"
                                        >
                                            {{ __('frontend.admin.capabilities.' . $capability . '.description') }}
                                        </span>
                                        @if ($protected)
                                            <span
                                                class="{{ $isShadcn ? 'text-xs text-slate-500' : 'text-xs text-stone-500' }} block"
                                            >
                                                {{ __('frontend.admin.protected_capability') }}
                                            </span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <x-input-error :messages="$errors->get('selectedCapabilities.' . $availableRole['value'])" />

                    <button type="submit" class="{{ $secondaryButtonClass }}">
                        {{ __('frontend.admin.update_capabilities') }}
                    </button>
                </form>
            </article>
        @endforeach
    </div>
</section>
