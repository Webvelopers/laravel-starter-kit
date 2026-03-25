@props([
    'showLabel',
    'hideLabel',
    'visible' => false,
    'toggleAction',
])

<div class="relative" data-password-toggle-root>
    <x-auth.input :type="$visible ? 'text' : 'password'" {{ $attributes }} />

    <button
        type="button"
        class="password-toggle-button"
        wire:click="{{ $toggleAction }}"
        aria-pressed="{{ $visible ? 'true' : 'false' }}"
    >
        {{ $visible ? $hideLabel : $showLabel }}
    </button>
</div>
