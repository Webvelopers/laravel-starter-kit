<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait HandlesControllerRequests
{
    /**
     * @template TRequest of Request
     *
     * @param  class-string<TRequest>  $requestClass
     * @param  array<string, mixed>  $data
     * @return TRequest
     */
    protected function makeRequest(string $requestClass, array $data, string $method, string $uri): Request
    {
        $currentRequest = request();

        /** @var TRequest $request */
        $request = $requestClass::create(
            $uri,
            $method,
            $data,
            $currentRequest->cookies->all(),
            [],
            $currentRequest->server->all(),
        );

        $request->headers->replace($currentRequest->headers->all());
        $request->setLaravelSession($currentRequest->hasSession() ? $currentRequest->session() : app('session.store'));
        $request->setUserResolver($currentRequest->getUserResolver());
        $request->setRouteResolver($currentRequest->getRouteResolver());

        if ($request instanceof FormRequest) {
            $request->setContainer(app())->setRedirector(app('redirect'));
        }

        return $request;
    }

    protected function normalizeControllerResponse(mixed $response, Request $request): ?RedirectResponse
    {
        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        return $response instanceof RedirectResponse ? $response : null;
    }

    protected function reportValidationException(ValidationException $exception): void
    {
        $this->setErrorBag($exception->validator->errors());
    }

    protected function reportHttpResponseException(HttpResponseException $exception, Request $request): ?RedirectResponse
    {
        $response = $exception->getResponse();

        return $response instanceof RedirectResponse ? $response : $this->normalizeControllerResponse($response, $request);
    }
}
