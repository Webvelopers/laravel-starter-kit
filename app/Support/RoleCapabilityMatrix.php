<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\AppSettingKey;
use App\Enums\UserRole;
use App\Models\AppSetting;

final class RoleCapabilityMatrix
{
    /**
     * @return list<string>
     */
    public static function capabilitiesFor(UserRole $role): array
    {
        $matrix = self::matrix();
        $configured = $matrix[$role->value] ?? $role->defaultCapabilityKeys();

        $capabilities = array_values(array_unique(array_filter(
            $configured,
            static fn (string $capability): bool => in_array($capability, UserRole::allCapabilityKeys(), true),
        )));

        return array_values(array_unique([
            ...$capabilities,
            ...$role->protectedCapabilityKeys(),
        ]));
    }

    /**
     * @return array<string, list<string>>
     */
    public static function matrix(): array
    {
        $stored = AppSetting::array(AppSettingKey::RoleCapabilities);
        $defaults = self::defaultMatrix();

        $matrix = [];

        foreach (UserRole::cases() as $role) {
            $configured = $stored[$role->value] ?? $defaults[$role->value];

            $matrix[$role->value] = is_array($configured)
                ? array_values(array_filter($configured, is_string(...)))
                : $defaults[$role->value];
        }

        return $matrix;
    }

    /**
     * @param  list<string>  $capabilities
     */
    public static function update(UserRole $role, array $capabilities): void
    {
        $current = self::matrix();

        $current[$role->value] = array_values(array_unique([
            ...array_values(array_filter(
                $capabilities,
                static fn (string $capability): bool => in_array($capability, UserRole::allCapabilityKeys(), true),
            )),
            ...$role->protectedCapabilityKeys(),
        ]));

        AppSetting::setArray(AppSettingKey::RoleCapabilities, $current);
    }

    /**
     * @return array<string, list<string>>
     */
    private static function defaultMatrix(): array
    {
        $matrix = [];

        foreach (UserRole::cases() as $role) {
            $matrix[$role->value] = $role->defaultCapabilityKeys();
        }

        return $matrix;
    }
}
