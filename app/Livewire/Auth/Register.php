<?php

namespace App\Livewire\Auth;

use App\Traits\HasConfigs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    use HasConfigs;

    public $name;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public $showNameField = false;
    public $showEmailField = true;
    public $showPasswordField = false;
    public $showPasswordConfirmationField = false;
    public $showEmailRegistration = true;

    public function rules()
    {
        if (!$this->settings->enable_email_registration) {
            return [];
        }

        $nameValidationRules = [];
        if (config('settings.registration_include_name_field')) {
            $nameValidationRules = ['name' => 'required'];
        }

        $passwordValidationRules = ['password' => 'required|min:8'];
        if (config('settings.registration_include_password_confirmation_field')) {
            $passwordValidationRules['password'] .= '|confirmed';
        }
        return array_merge(
            $nameValidationRules,
            ['email' => 'required|email|unique:users'],
            $passwordValidationRules
        );
    }

    public function mount()
    {
        $this->loadConfigs();

        if (!$this->settings->registration_enabled) {
            session()->flash('error', config('language.register.registrations_disabled', 'Registrations are currently disabled.'));
            redirect()->route('auth.login');
            return;
        }

        if (!$this->settings->enable_email_registration) {
            $this->showEmailRegistration = false;
            $this->showNameField = false;
            $this->showEmailField = false;
            $this->showPasswordField = false;
            $this->showPasswordConfirmationField = false;
            return;
        }

        if ($this->settings->registration_include_name_field) {
            $this->showNameField = true;
        }

        if ($this->settings->registration_show_password_same_screen) {
            $this->showPasswordField = true;

            if ($this->settings->registration_include_password_confirmation_field) {
                $this->showPasswordConfirmationField = true;
            }
        }
    }

    public function register()
    {
        if (!$this->settings->registration_enabled) {
            session()->flash('error', config('language.register.registrations_disabled', 'Registrations are currently disabled.'));
            return redirect()->route('auth.login');
        }

        if (!$this->settings->enable_email_registration) {
            session()->flash('error', config('language.register.email_registration_disabled', 'Email registration is currently disabled. Please use social login.'));
            return redirect()->route('auth.register');
        }

        if (!$this->showPasswordField) {
            if ($this->settings->registration_include_name_field) {
                $this->validateOnly('name');
            }
            $this->validateOnly('email');

            $this->showPasswordField = true;
            if ($this->settings->registration_include_password_confirmation_field) {
                $this->showPasswordConfirmationField = true;
            }
            $this->showNameField = false;
            $this->showEmailField = false;
            $this->js("setTimeout(function(){ window.dispatchEvent(new CustomEvent('focus-password', {})); }, 10);");
            return;
        }

        $this->validate();

        $userData = [
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];

        if ($this->settings->registration_include_name_field) {
            $userData['name'] = $this->name;
        }

        $user = app(config('auth.providers.users.model'))->create($userData);

        event(new Registered($user));

        Auth::login($user, true);

        if (config('settings.registration_require_email_verification')) {
            return redirect()->route('verification.notice');
        }

        if (session()->get('url.intended') != route('logout.get')) {
            session()->regenerate();
            redirect()->intended(config('settings.redirect_after_auth'));
        } else {
            session()->regenerate();
            return redirect(config('settings.redirect_after_auth'));
        }
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.guest');
    }
}

