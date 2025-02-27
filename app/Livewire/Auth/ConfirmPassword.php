<?php

namespace App\Livewire\Auth;

use App\Traits\HasConfigs;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ConfirmPassword extends Component
{
    use HasConfigs;

    #[Validate('required|current_password')]
    public $password = '';

    public function mount(){
        $this->loadConfigs();
    }

    public function confirm()
    {
        $this->validate();

        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(config('settings.redirect_after_auth'));
    }

    public function render()
    {
        return view('livewire.auth.confirm-password')
            ->layout('layouts.guest');
    }
}
