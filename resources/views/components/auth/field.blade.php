@props([
    'label',
    'error' => null,
])

<label class="form-field">
    <span>{{ $label }}</span>
    {{ $slot }}
</label>

@if (filled($error))
    <x-input-error :messages="$errors->get($error)" />
@endif
