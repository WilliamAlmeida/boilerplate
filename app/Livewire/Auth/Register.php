<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use App\Traits\HelperFunctions;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Computed;
use App\Livewire\Auth\BaseComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class Register extends BaseComponent
{
    use Toast;
    use HelperFunctions;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $timezone;
    public $country = 'BR';
    public $terms = false;

    public function register()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()->min(6)],
            'timezone' => ['required', 'timezone'],
        ], attributes: [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
        ]);

        if (!$this->terms) {
            $this->warning('Aceite os termos de uso para continuar.');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'timezone' => $this->timezone,
            'type' => User::USER,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('home'), navigate: true);
    }

    #[Computed]
    public function timezones()
    {
        return $this->getTimezones($this->country);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}