<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Concerns\HandlesControllerRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use Livewire\Component;

final class TwoFactorChallengeForm extends Component
{
    use HandlesControllerRequests;

    public string $code = '';

    public string $recoveryCode = '';

    public function authenticate(TwoFactorAuthenticatedSessionController $controller): void
    {
        $this->resetErrorBag();

        $request = $this->makeRequest(TwoFactorLoginRequest::class, [
            'code' => $this->code,
            'recovery_code' => $this->recoveryCode,
        ], 'POST', route('two-factor.login.store'));

        try {
            $response = $controller->store($request);
        } catch (ValidationException $exception) {
            $this->reportValidationException($exception);

            return;
        } catch (HttpResponseException $exception) {
            $redirect = $this->reportHttpResponseException($exception, $request);

            if ($redirect instanceof \Illuminate\Http\RedirectResponse) {
                $this->redirect($redirect->getTargetUrl(), navigate: true);
            }

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
            'code' => ['nullable', 'string'],
            'recoveryCode' => ['nullable', 'string'],
        ], [], [
            'recoveryCode' => 'recovery_code',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.two-factor-challenge-form');
    }
}
