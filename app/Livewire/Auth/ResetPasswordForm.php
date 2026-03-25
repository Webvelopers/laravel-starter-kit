<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Concerns\HandlesControllerRequests;
use App\Livewire\Concerns\InteractsWithPasswordStrength;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Livewire\Component;

final class ResetPasswordForm extends Component
{
    use HandlesControllerRequests;
    use InteractsWithPasswordStrength;

    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function mount(string $token, string $email = ''): void
    {
        $this->token = $token;
        $oldEmail = old('email');
        $this->email = is_string($oldEmail) ? $oldEmail : $email;
    }

    public function resetPassword(NewPasswordController $controller): void
    {
        $this->resetErrorBag();

        $request = $this->makeRequest(Request::class, [
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ], 'POST', route('password.update'));

        try {
            $response = $controller->store($request);
        } catch (ValidationException $exception) {
            $this->reportValidationException($exception);

            return;
        }

        $redirect = $this->normalizeControllerResponse($response, $request);

        if ($redirect instanceof \Illuminate\Http\RedirectResponse) {
            $this->redirect($redirect->getTargetUrl(), navigate: true);
        }
    }

    public function updated(string $property): void
    {
        $this->validateOnly($property, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'passwordConfirmation' => ['required', 'same:password'],
        ], [], [
            'passwordConfirmation' => 'password_confirmation',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.reset-password-form', [
            'passwordStrength' => $this->passwordStrength($this->password),
        ]);
    }
}
