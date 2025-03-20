<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Locked;
use App\Livewire\Auth\BaseComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login extends BaseComponent
{
    public $email = '';
    public $password = '';
    public $remember = false;

    #[Locked]
    public $display_demo = false;

    public function mount()
    {
        $this->display_demo = request()->has('demo');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = User::withTrashed()->where('email', $this->email)->first();

        if (!$usuario) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }
        
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        session()->regenerate();

        $this->redirect(session()->pull('url.intended', route('home')), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}