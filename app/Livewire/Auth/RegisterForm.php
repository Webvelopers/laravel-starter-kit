<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Concerns\HandlesControllerRequests;
use App\Livewire\Concerns\InteractsWithPasswordStrength;
use App\Models\AppSetting;
use App\Support\HumanVerificationChallenge;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Livewire\Component;

final class RegisterForm extends Component
{
    use HandlesControllerRequests;
    use InteractsWithPasswordStrength;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public string $humanVerificationAnswer = '';

    public bool $registrationHumanVerificationEnabled = false;

    public ?string $humanVerificationImage = null;

    public bool $showPassword = false;

    public bool $showPasswordConfirmation = false;

    public function mount(HumanVerificationChallenge $challenge): void
    {
        $this->name = $this->oldString('name');
        $this->email = $this->oldString('email');
        $this->humanVerificationAnswer = $this->oldString('human_verification_answer');
        $this->registrationHumanVerificationEnabled = AppSetting::registrationHumanVerificationEnabled();
        $this->humanVerificationImage = $this->registrationHumanVerificationEnabled ? $challenge->image() : null;
    }

    public function refreshHumanVerification(HumanVerificationChallenge $challenge): void
    {
        $challenge->refresh();
        $this->humanVerificationImage = $challenge->image();
    }

    public function togglePasswordVisibility(): void
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function togglePasswordConfirmationVisibility(): void
    {
        $this->showPasswordConfirmation = ! $this->showPasswordConfirmation;
    }

    public function register(CreatesNewUsers $creator, StatefulGuard $guard, HumanVerificationChallenge $challenge): void
    {
        $this->resetErrorBag();

        try {
            $user = $creator->create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->passwordConfirmation,
                'human_verification_answer' => $this->humanVerificationAnswer,
            ]);
        } catch (ValidationException $exception) {
            $this->reportValidationException($exception);
            $this->humanVerificationImage = $this->registrationHumanVerificationEnabled ? $challenge->image() : null;

            return;
        }

        event(new Registered($user));

        $guard->login($user);
        session()->regenerate();

        $this->redirect(config('fortify.home'), navigate: true);
    }

    public function updated(string $property): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'passwordConfirmation' => ['required', 'same:password'],
        ];

        if ($this->registrationHumanVerificationEnabled) {
            $rules['humanVerificationAnswer'] = ['required', 'string'];
        }

        $this->validateOnly($property, $rules, [], [
            'humanVerificationAnswer' => 'human_verification_answer',
            'passwordConfirmation' => 'password_confirmation',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.register-form', [
            'passwordStrength' => $this->passwordStrength($this->password),
        ]);
    }

    private function oldString(string $key): string
    {
        $value = old($key);

        return is_string($value) ? $value : '';
    }
}
