<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

final class AppSettingsSeeder extends Seeder
{
    /**
     * Seed the application's app settings.
     */
    public function run(): void
    {
        AppSetting::setRegistrationHumanVerificationEnabled(true);
    }
}
