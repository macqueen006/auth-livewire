<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Traits\HasConfigs;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    use HasConfigs;

    public $recovery = false;
    public $google2fa;

    #[Validate('required|min:6')]
    public $auth_code;
    public $recovery_code;

    public function mount()
    {
        $this->loadConfigs();
        $this->recovery = false;
    }

    public function switchToRecovery()
    {
        $this->recovery = !$this->recovery;
        if($this->recovery){
            $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-auth-2fa-recovery-code', {})); }, 10);");
        } else {
            $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-auth-2fa-auth-code', {})); }, 10);");
        }
        return;
    }

    #[On('submitCode')]
    public function submitCode($code)
    {
        $this->auth_code = $code;
        $this->validate();

        $user = User::find(session()->get('login.id'));
        $secret = decrypt($user->two_factor_secret);
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $code);

        if($valid){
            $this->loginUser($user);
        } else {
            $this->addError('auth_code', 'Invalid authentication code. Please try again.');
        }
    }

    public function submit_recovery_code(){
        $user = User::find(session()->get('login.id'));
        $valid = in_array($this->recovery_code, json_decode(decrypt($user->two_factor_recovery_codes)));

        if ($valid) {
            $this->loginUser($user);
        } else {
            $this->addError('recovery_code', 'This is an invalid recovery code. Please try again.');
        }
    }

    public function loginUser($user){
        Auth::login($user);

        // clear out the session that is used to determine if the user can visit the 2fa challenge page.
        session()->forget('login.id');

        event(new Login(auth()->guard('web'), $user, true));

        if(session()->get('url.intended') != route('logout.get')){
            return redirect()->intended(config('settings.redirect_after_auth'));
        } else {
            return redirect(config('settings.redirect_after_auth'));
        }
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')->layout('layouts.guest');
    }
}
