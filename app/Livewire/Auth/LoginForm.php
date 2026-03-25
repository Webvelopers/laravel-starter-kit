<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Concerns\HandlesControllerRequests;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\CanonicalizeUsername;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RedirectsIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Livewire\Component;

final class LoginForm extends Component
{
    use HandlesControllerRequests;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        $this->email = $this->oldString('email');
    }

    public function authenticate(): void
    {
        $this->resetErrorBag();

        $request = $this->makeRequest(LoginRequest::class, [
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
        ], 'POST', route('login'));

        try {
            $request->validateResolved();

            $response = $this->loginPipeline($request)->then(fn (LoginRequest $request): LoginResponse => app(LoginResponse::class));
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
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.login-form');
    }

    private function oldString(string $key): string
    {
        $value = old($key);

        return is_string($value) ? $value : '';
    }

    private function loginPipeline(LoginRequest $request): Pipeline
    {
        if (Fortify::$authenticateThroughCallback) {
            /** @var array<int, class-string|callable> $pipeline */
            $pipeline = array_values(array_filter((array) call_user_func(Fortify::$authenticateThroughCallback, $request)));

            return (new Pipeline(app()))->send($request)->through($pipeline);
        }

        if (is_array(config('fortify.pipelines.login'))) {
            /** @var array<int, class-string|callable> $pipeline */
            $pipeline = array_values(array_filter(config('fortify.pipelines.login')));

            return (new Pipeline(app()))->send($request)->through($pipeline);
        }

        return (new Pipeline(app()))->send($request)->through(array_values(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            config('fortify.lowercase_usernames') ? CanonicalizeUsername::class : null,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectsIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ])));
    }
}
