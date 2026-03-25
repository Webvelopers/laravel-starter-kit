<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;

final class UserRoleAssignment extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'role',
    ];

    public static function roleFor(?User $user): UserRole
    {
        if (! $user instanceof User) {
            return UserRole::User;
        }

        $role = self::query()
            ->where('user_id', $user->getKey())
            ->value('role');

        return $role instanceof UserRole ? $role : (is_string($role) ? UserRole::from($role) : UserRole::User);
    }

    public static function assign(User $user, UserRole $role): void
    {
        self::query()->updateOrCreate(
            ['user_id' => $user->getKey()],
            ['role' => $role->value],
        );
    }

    /**
     * @param  list<UserRole>  $roles
     */
    public static function userHasAnyRole(User $user, array $roles): bool
    {
        return in_array(self::roleFor($user), $roles, true);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'role' => UserRole::class,
        ];
    }
}
