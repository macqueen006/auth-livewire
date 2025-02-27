<?php

namespace App\Livewire\Auth;
use App\Traits\HasConfigs;
use Illuminate\Auth\Events\Verified;
use Livewire\Component;

class Verification extends Component
{
    use HasConfigs;

    public function mount(){
        $this->loadConfigs();
    }

    public function resend()
    {
        $user = auth()->user();
        if ($user->hasVerifiedEmail()) {
            redirect('/');
        }

        $user->sendEmailVerificationNotification();

        event(new Verified($user));

        $this->dispatch('resent');
        session()->flash('resent');
    }

    public function render()
    {
        return view('livewire.auth.verification')->layout('layouts.guest');
    }
}
