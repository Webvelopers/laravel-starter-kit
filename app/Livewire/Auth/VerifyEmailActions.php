<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class VerifyEmailActions extends Component
{
    public function resend(): void
    {
        $user = request()->user();

        if ($user === null || $user->hasVerifiedEmail()) {
            $this->redirectRoute('dashboard', navigate: true);

            return;
        }

        $user->sendEmailVerificationNotification();
        session()->flash('status', 'verification-link-sent');

        $this->redirectRoute('verification.notice', navigate: true);
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('home', navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.verify-email-actions');
    }
}
