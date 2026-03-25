<?php

declare(strict_types=1);

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class LogoutButton extends Component
{
    public string $label = '';

    public string $class = '';

    public function logout(): void
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('home', navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.shared.logout-button');
    }
}
