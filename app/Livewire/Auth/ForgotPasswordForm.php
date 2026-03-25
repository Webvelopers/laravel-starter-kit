<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Concerns\HandlesControllerRequests;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Requests\SendPasswordResetLinkRequest;
use Livewire\Component;

final class ForgotPasswordForm extends Component
{
    use HandlesControllerRequests;

    public string $email = '';

    public function mount(): void
    {
        $this->email = $this->oldString('email');
    }

    public function sendResetLink(PasswordResetLinkController $controller): void
    {
        $this->resetErrorBag();

        $request = $this->makeRequest(SendPasswordResetLinkRequest::class, [
            'email' => $this->email,
        ], 'POST', route('password.email'));

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
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.forgot-password-form');
    }

    private function oldString(string $key): string
    {
        $value = old($key);

        return is_string($value) ? $value : '';
    }
}
