<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Support\RoleCapabilityMatrix;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class RoleCapabilitiesSection extends Component
{
    /**
     * @var array<string, list<string>>
     */
    public array $selectedCapabilities = [];

    public function mount(): void
    {
        foreach (UserRole::cases() as $role) {
            $this->selectedCapabilities[$role->value] = RoleCapabilityMatrix::capabilitiesFor($role);
        }
    }

    public function updateRoleCapabilities(string $roleValue): void
    {
        $role = UserRole::from($roleValue);
        $field = 'selectedCapabilities.'.$roleValue;

        $this->validate([
            $field => ['nullable', 'array'],
            $field.'.*' => ['string', Rule::in(UserRole::allCapabilityKeys())],
        ]);

        /** @var list<string> $capabilities */
        $capabilities = array_values(array_filter(
            Arr::wrap($this->selectedCapabilities[$roleValue] ?? []),
            is_string(...),
        ));

        RoleCapabilityMatrix::update($role, $capabilities);
        session()->flash('status', 'role-capabilities-updated');

        $this->redirectRoute('admin.settings', navigate: true);
    }

    public function updated(string $property): void
    {
        if (! str_starts_with($property, 'selectedCapabilities.')) {
            return;
        }

        $this->validateOnly($property, [
            'selectedCapabilities.*' => ['nullable', 'array'],
            'selectedCapabilities.*.*' => ['string', Rule::in(UserRole::allCapabilityKeys())],
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.role-capabilities-section', [
            'allCapabilities' => UserRole::allCapabilityKeys(),
            'availableRoles' => collect(UserRole::cases())
                ->map(static fn (UserRole $role): array => [
                    'value' => $role->value,
                    'label' => __('frontend.roles.'.$role->value),
                    'protected_capabilities' => $role->protectedCapabilityKeys(),
                ])
                ->values()
                ->all(),
        ]);
    }
}
