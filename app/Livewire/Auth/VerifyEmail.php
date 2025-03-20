<?php

namespace App\Livewire\Auth;

use App\Livewire\Auth\BaseComponent;
use Illuminate\Support\Facades\RateLimiter;
use Mary\Traits\Toast;

class VerifyEmail extends BaseComponent
{
    use Toast;

    public function mount()
    {
        if(request()->user()->hasVerifiedEmail()) {
            $this->redirect(session()->pull('url.intended', route('home', absolute: false)), navigate: true);
        }
    }

    public function resendVerificationEmail()
    {
        if(request()->user()->hasVerifiedEmail()) {
            return $this->redirect(session()->pull('url.intended', route('home', absolute: false)), navigate: true);
        }

        $executed = RateLimiter::attempt(
            $key = 'verification-email-'.request()->user()->id,
            $perTwoMinutes = 6,
            function() {
                request()->user()->sendEmailVerificationNotification();
                session()->flash('status', 'verification-link-sent');
            },
            $decayRate = 60,
        );

        if(!$executed) {
            $this->warning('Many requests have been made in a short period of time. Please wait a minute before trying again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}