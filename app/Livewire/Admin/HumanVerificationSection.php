<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Component;

final class HumanVerificationSection extends Component
{
    public bool $registrationHumanVerificationEnabled = false;

    public function mount(): void
    {
        $this->registrationHumanVerificationEnabled = AppSetting::registrationHumanVerificationEnabled();
    }

    public function save(): void
    {
        $this->validate([
            'registrationHumanVerificationEnabled' => ['required', 'boolean'],
        ]);

        AppSetting::setRegistrationHumanVerificationEnabled($this->registrationHumanVerificationEnabled);
        session()->flash('status', 'human-verification-updated');

        $this->redirectRoute('admin.settings', navigate: true);
    }

    public function updated(string $property): void
    {
        $this->validateOnly($property, [
            'registrationHumanVerificationEnabled' => ['required', 'boolean'],
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.human-verification-section');
    }
}
