<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait InteractsWithPasswordStrength
{
    /**
     * @return array{
     *     score: int,
     *     percentage: int,
     *     label: string,
     *     checks: array<string, bool>
     * }
     */
    protected function passwordStrength(string $password): array
    {
        $checks = [
            'length' => mb_strlen($password) >= 8,
            'mixed_case' => preg_match('/(?=.*[a-z])(?=.*[A-Z])/', $password) === 1,
            'number' => preg_match('/\d/', $password) === 1,
            'symbol' => preg_match('/[^A-Za-z0-9]/', $password) === 1,
        ];

        $score = count(array_filter($checks));

        return [
            'score' => $score,
            'percentage' => $score * 25,
            'label' => match (true) {
                $password === '' => __('frontend.password_strength.empty'),
                $score <= 1 => __('frontend.password_strength.weak'),
                $score <= 3 => __('frontend.password_strength.medium'),
                default => __('frontend.password_strength.strong'),
            },
            'checks' => $checks,
        ];
    }
}
