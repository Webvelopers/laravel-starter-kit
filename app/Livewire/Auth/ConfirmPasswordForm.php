<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Concerns\HandlesControllerRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Livewire\Component;

final class ConfirmPasswordForm extends Component
{
    use HandlesControllerRequests;

    public string $password = '';

    public function confirmPassword(ConfirmablePasswordController $controller): void
    {
        $this->resetErrorBag();

        $request = $this->makeRequest(Request::class, [
            'password' => $this->password,
        ], 'POST', route('password.confirm.store'));

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
            'password' => ['required', 'string'],
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.confirm-password-form');
    }
}
