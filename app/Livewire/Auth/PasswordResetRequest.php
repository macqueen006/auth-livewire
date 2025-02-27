<?php

namespace App\Livewire\Auth;

use App\Traits\HasConfigs;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PasswordResetRequest extends Component
{
    use HasConfigs;

    #[Validate('required|email')]
    public $email = null;
    public $emailSentMessage = false;

    public function mount()
    {
        $this->loadConfigs();
    }

    public function sendResetPasswordLink()
    {
        $this->validate();

        $response = Password::broker()->sendResetLink(['email' => $this->email]);

        if ($response == Password::RESET_LINK_SENT) {
            $this->emailSentMessage = trans($response);

            return;
        }

        $this->addError('email', trans($response));
    }

    public function render()
    {
        return view('livewire.auth.password-reset-request')->layout('layouts.guest');
    }
}
