<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Actions\Fortify\UpdateUserPassword;
use App\Livewire\Concerns\InteractsWithPasswordStrength;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

final class SecuritySection extends Component
{
    use InteractsWithPasswordStrength;

    public string $currentPassword = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public string $code = '';

    public function updatePassword(UpdateUserPassword $updater): void
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        try {
            $updater->update($user, [
                'current_password' => $this->currentPassword,
                'password' => $this->password,
                'password_confirmation' => $this->passwordConfirmation,
            ]);
        } catch (ValidationException $exception) {
            $this->setErrorBag($exception->validator->errors());

            return;
        }

        $this->reset(['currentPassword', 'password', 'passwordConfirmation']);
        session()->flash('status', 'password-updated');

        $this->redirectRoute('profile', navigate: true);
    }

    public function enableTwoFactor(EnableTwoFactorAuthentication $enable): void
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $enable($user, false);
        session()->flash('status', 'two-factor-authentication-enabled');

        $this->redirectRoute('profile', navigate: true);
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disable): void
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $disable($user);
        session()->flash('status', 'two-factor-authentication-disabled');

        $this->redirectRoute('profile', navigate: true);
    }

    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirm): void
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        try {
            $confirm($user, $this->code);
        } catch (ValidationException $exception) {
            $this->setErrorBag($exception->validator->errors());

            return;
        }

        $this->reset('code');
        session()->flash('status', 'two-factor-authentication-confirmed');

        $this->redirectRoute('profile', navigate: true);
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $generate($user);
        session()->flash('status', 'recovery-codes-generated');

        $this->redirectRoute('profile', navigate: true);
    }

    public function updated(string $property): void
    {
        $this->validateOnly($property, [
            'currentPassword' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'passwordConfirmation' => ['required', 'same:password'],
            'code' => ['nullable', 'string'],
        ], [], [
            'currentPassword' => 'current_password',
            'passwordConfirmation' => 'password_confirmation',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        return view('livewire.profile.security-section', [
            'user' => $user,
            'twoFactorEnabled' => $user?->two_factor_secret !== null,
            'twoFactorConfirmed' => $user?->two_factor_confirmed_at !== null,
            'passwordStrength' => $this->passwordStrength($this->password),
        ]);
    }
}
