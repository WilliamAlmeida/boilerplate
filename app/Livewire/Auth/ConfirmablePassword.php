<?php

namespace App\Livewire\Auth;

use Illuminate\Validation\Rules;
use App\Livewire\Auth\BaseComponent;

class ConfirmablePassword extends BaseComponent
{
    public $password;

    public function confirmPassword()
    {
        $this->validate([
            'password' => ['required', Rules\Password::defaults()->min(6)],
        ], attributes: [
            'password' => __('Password'),
        ]);

        if (! auth()->guard('web')->validate([
            'email' => request()->user()->email,
            'password' => $this->password,
        ])) {
            $this->addError('password', __('auth.password'));
        }

        session()->put('auth.password_confirmed_at', time());

        $this->redirect(session()->pull('url.intended', route('home', absolute: false)), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.confirm-password');
    }
}