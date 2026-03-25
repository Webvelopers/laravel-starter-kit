<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use App\Models\UserFrontendPreference;
use App\Models\UserRoleAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

final class PersonalSection extends Component
{
    public string $locale = '';

    public string $frontendTemplate = '';

    public string $name = '';

    public string $email = '';

    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        /** @var list<string> $supportedLocales */
        $supportedLocales = config('frontend.locales.supported', []);
        /** @var list<string> $supportedTemplates */
        $supportedTemplates = config('frontend.templates.supported', []);

        $this->locale = $this->resolveString(session('locale'), config('frontend.locales.default', config('app.locale')));
        $this->frontendTemplate = $this->resolveString(session('frontend_template'), config('frontend.templates.default', 'default'));
        $this->name = $user instanceof User ? $user->name : '';
        $this->email = $user instanceof User ? $user->email : '';

        if (! in_array($this->locale, $supportedLocales, true)) {
            $this->locale = $this->resolveString(config('frontend.locales.default'), config('app.locale'));
        }

        if (! in_array($this->frontendTemplate, $supportedTemplates, true)) {
            $this->frontendTemplate = $this->resolveString(config('frontend.templates.default'), 'default');
        }
    }

    public function updateLocale(): void
    {
        /** @var list<string> $supportedLocales */
        $supportedLocales = config('frontend.locales.supported', []);

        $this->validate([
            'locale' => ['required', 'string', Rule::in($supportedLocales)],
        ]);

        session()->put('locale', $this->locale);

        $this->redirectRoute('profile', navigate: true);
    }

    public function updateTemplate(): void
    {
        /** @var list<string> $supportedTemplates */
        $supportedTemplates = config('frontend.templates.supported', []);

        $this->validate([
            'frontendTemplate' => ['required', 'string', Rule::in($supportedTemplates)],
        ], [], [
            'frontendTemplate' => 'frontend_template',
        ]);

        $user = Auth::user();

        if ($user !== null) {
            UserFrontendPreference::updateTemplateFor($user, $this->frontendTemplate);
        }

        session()->put('frontend_template', $this->frontendTemplate);
        session()->flash('status', 'frontend-template-updated');

        $this->redirectRoute('profile', navigate: true);
    }

    public function updated(string $property): void
    {
        /** @var list<string> $supportedLocales */
        $supportedLocales = config('frontend.locales.supported', []);
        /** @var list<string> $supportedTemplates */
        $supportedTemplates = config('frontend.templates.supported', []);

        $this->validateOnly($property, [
            'locale' => ['required', 'string', Rule::in($supportedLocales)],
            'frontendTemplate' => ['required', 'string', Rule::in($supportedTemplates)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ], [], [
            'frontendTemplate' => 'frontend_template',
        ]);
    }

    public function updateProfileInformation(UpdateUserProfileInformation $updater): void
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        try {
            $updater->update($user, [
                'name' => $this->name,
                'email' => $this->email,
            ]);
        } catch (ValidationException $exception) {
            $this->setErrorBag($exception->validator->errors());

            return;
        }

        session()->flash('status', 'profile-information-updated');

        $this->redirectRoute('profile', navigate: true);
    }

    public function resendVerification(): void
    {
        $user = Auth::user();

        if ($user === null || $user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();
        session()->flash('status', 'verification-link-sent');

        $this->redirectRoute('profile', navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.profile.personal-section', [
            'currentUserRole' => UserRoleAssignment::roleFor(Auth::user()),
            'supportedLocales' => config('frontend.locales.supported', []),
            'supportedTemplates' => config('frontend.templates.supported', []),
            'defaultTemplate' => config('frontend.templates.default', 'default'),
        ]);
    }

    private function resolveString(mixed $value, mixed $fallback): string
    {
        if (is_string($value)) {
            return $value;
        }

        return is_string($fallback) ? $fallback : '';
    }
}
