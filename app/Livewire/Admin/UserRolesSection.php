<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserRoleAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class UserRolesSection extends Component
{
    /**
     * @var array<int, string>
     */
    public array $roleSelections = [];

    public function mount(): void
    {
        $assignments = UserRoleAssignment::query()->pluck('role', 'user_id');

        foreach (User::query()->orderBy('name')->get() as $user) {
            $role = $assignments[$user->id] ?? UserRole::User->value;
            $this->roleSelections[$user->id] = match (true) {
                $role instanceof UserRole => $role->value,
                is_string($role) => $role,
                default => UserRole::User->value,
            };
        }
    }

    public function updateRole(int $userId): void
    {
        $user = User::query()->findOrFail($userId);

        abort_if(Auth::id() === $user->id, 422, __('frontend.admin.role_self_update_forbidden'));

        $field = 'roleSelections.'.$userId;

        $this->validate([
            $field => ['required', 'string', Rule::in(collect(UserRole::cases())->map(static fn (UserRole $role): string => $role->value)->all())],
        ]);

        UserRoleAssignment::assign($user, UserRole::from($this->roleSelections[$userId]));
        session()->flash('status', 'user-role-updated');

        $this->redirectRoute('admin.settings', navigate: true);
    }

    public function updated(string $property): void
    {
        if (! str_starts_with($property, 'roleSelections.')) {
            return;
        }

        $this->validateOnly($property, [
            'roleSelections.*' => ['required', 'string', Rule::in(collect(UserRole::cases())->map(static fn (UserRole $role): string => $role->value)->all())],
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.user-roles-section', [
            'users' => User::query()->orderBy('name')->get(),
            'availableRoles' => collect(UserRole::cases())
                ->map(static fn (UserRole $role): array => [
                    'value' => $role->value,
                    'label' => __('frontend.roles.'.$role->value),
                ])
                ->values()
                ->all(),
        ]);
    }
}
