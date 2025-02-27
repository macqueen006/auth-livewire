<?php

namespace App\Livewire\Auth;

use App\Traits\HasConfigs;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    use HasConfigs;

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    #[Validate('bool')]

    public $rememberMe = false;

    public $showPasswordField = false;

    public $showIdentifierInput = true;
    public $showSocialProviderInfo = false;

    public $language = [];

    public $twoFactorEnabled = true;

    public $userSocialProviders = ['facebook'];

    public $userModel = null;

    public function mount(){
        $this->loadConfigs();
        $this->twoFactorEnabled = $this->settings->enable_2fa;
        $this->userModel = app(config('auth.providers.users.model'));
    }

    public function editIdentity(){
        if($this->showPasswordField){
            $this->showPasswordField = false;
            return;
        }

        $this->showIdentifierInput = true;
        $this->showSocialProviderInfo = false;
    }

    public function authenticate()
    {

        if(!$this->showPasswordField){
            $this->validateOnly('email');
            $userTryingToValidate = $this->userModel->where('email', $this->email)->first();
            if(!is_null($userTryingToValidate)){
                if(is_null($userTryingToValidate->password)){
                    $this->userSocialProviders = [];
                    // User is attempting to login and password is null. Need to show Social Provider info
                    foreach($userTryingToValidate->socialProviders->all() as $provider){
                        array_push($this->userSocialProviders, $provider->provider_slug);
                    }
                    $this->showIdentifierInput = false;
                    $this->showSocialProviderInfo = true;
                    return;
                }
            }

            // Check if account exists before login and handle error if user is not found
            if(config('settings.check_account_exists_before_login') && is_null($userTryingToValidate)){
                $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-email', {})); }, 10);");
                $this->addError('email', trans(config('language.login.couldnt_find_your_account')));
                return;
            }

            $this->showPasswordField = true;
            $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-password', {})); }, 10);");
            return;
        }


        $this->validate();

        $credentials = ['email' => $this->email, 'password' => $this->password];

        if(!Auth::validate($credentials)){
            $this->addError('password', trans('auth.failed'));
            return;
        }

        $userAttemptingLogin = $this->userModel->where('email', $this->email)->first();

        if(!isset($userAttemptingLogin->id)){
            $this->addError('password', trans('auth.failed'));
            return;
        }

        if($this->twoFactorEnabled && !is_null($userAttemptingLogin->two_factor_confirmed_at)){
            // We want this user to login via 2fa
            session()->put([
                'login.id' => $userAttemptingLogin->getKey()
            ]);

            return redirect()->route('auth.two-factor-challenge');

        } else {
            if (!Auth::attempt($credentials, $this->rememberMe)) {
                $this->addError('password', trans('auth.failed'));
                return;
            }

            event(new Login(auth()->guard('web'), $this->userModel->where('email', $this->email)->first(), true));

            if(session()->get('url.intended') != route('logout.get')){
                session()->regenerate();
                redirect()->intended(config('settings.redirect_after_auth'));
            } else {
                session()->regenerate();
                return redirect(config('settings.redirect_after_auth'));
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
