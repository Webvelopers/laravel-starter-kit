@php
    $isShadcn = ($frontendTemplate ?? 'default') === 'shadcn';
    $sectionCardClass = $isShadcn ? 'rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_20px_60px_-36px_rgba(15,23,42,0.25)]' : 'rounded-[2rem] border border-stone-200 bg-white/90 p-6 shadow-[0_24px_80px_-40px_rgba(120,53,15,0.45)]';
    $sectionTitleClass = $isShadcn ? 'text-2xl font-semibold tracking-tight text-slate-950' : 'font-display text-2xl text-stone-950';
    $sectionDescriptionClass = $isShadcn ? 'mt-2 text-sm leading-7 text-slate-500' : 'mt-2 text-sm leading-7 text-stone-500';
    $tableWrapClass = $isShadcn ? 'mt-6 overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white' : 'mt-6 overflow-hidden rounded-[1.5rem] border border-stone-200 bg-white';
    $tableHeaderClass = $isShadcn ? 'bg-slate-50 text-left text-xs font-medium tracking-[0.18em] text-slate-500 uppercase' : 'bg-stone-50 text-left text-xs font-semibold tracking-[0.22em] text-stone-500 uppercase';
    $tableCellClass = $isShadcn ? 'border-t border-slate-200 px-4 py-4 text-sm text-slate-700' : 'border-t border-stone-200 px-4 py-4 text-sm text-stone-700';
    $badgeClass = $isShadcn ? 'inline-flex rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium tracking-[0.18em] text-slate-600 uppercase' : 'inline-flex rounded-full border border-stone-200 bg-white px-3 py-1 text-xs font-semibold tracking-[0.22em] text-stone-700 uppercase';
    $inputClass = $isShadcn ? 'w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-slate-900 transition outline-none focus:border-slate-950 focus:ring-2 focus:ring-slate-950/10' : 'w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-stone-900 transition outline-none focus:border-stone-900 focus:bg-white';
    $secondaryButtonClass = $isShadcn ? 'rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50' : 'rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-900 hover:bg-white';
@endphp

<section class="{{ $sectionCardClass }}">
    <h2 class="{{ $sectionTitleClass }}">{{ __('frontend.admin.user_roles_title') }}</h2>
    <p class="{{ $sectionDescriptionClass }}">{{ __('frontend.admin.user_roles_description') }}</p>

    <div class="{{ $tableWrapClass }}">
        <table class="min-w-full border-collapse">
            <thead class="{{ $tableHeaderClass }}">
                <tr>
                    <th class="px-4 py-3">{{ __('frontend.admin.user_name') }}</th>
                    <th class="px-4 py-3">{{ __('frontend.admin.user_email') }}</th>
                    <th class="px-4 py-3">{{ __('frontend.admin.user_role') }}</th>
                    <th class="px-4 py-3">{{ __('frontend.admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $managedUser)
                    <tr wire:key="managed-user-{{ $managedUser->id }}">
                        <td class="{{ $tableCellClass }}">{{ $managedUser->name }}</td>
                        <td class="{{ $tableCellClass }}">{{ $managedUser->email }}</td>
                        <td class="{{ $tableCellClass }}">
                            <span class="{{ $badgeClass }}">
                                {{ __('frontend.roles.' . ($roleSelections[$managedUser->id] ?? 'user')) }}
                            </span>
                        </td>
                        <td class="{{ $tableCellClass }}">
                            @if (auth()->id() === $managedUser->id)
                                <span class="{{ $isShadcn ? 'text-sm text-slate-500' : 'text-sm text-stone-500' }}">
                                    {{ __('frontend.admin.current_admin_account') }}
                                </span>
                            @else
                                <form
                                    wire:submit="updateRole({{ $managedUser->id }})"
                                    class="flex flex-col gap-3 md:flex-row md:items-center"
                                >
                                    <select
                                        wire:model="roleSelections.{{ $managedUser->id }}"
                                        class="{{ $inputClass }} max-w-xs"
                                    >
                                        @foreach ($availableRoles as $availableRole)
                                            <option value="{{ $availableRole['value'] }}">
                                                {{ $availableRole['label'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="{{ $secondaryButtonClass }}">
                                        {{ __('frontend.admin.update_role') }}
                                    </button>
                                </form>
                                <x-input-error
                                    :messages="$errors->get('roleSelections.' . $managedUser->id)"
                                    class="mt-3"
                                />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
