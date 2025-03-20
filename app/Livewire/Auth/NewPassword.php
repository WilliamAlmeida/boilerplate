<?php

namespace App\Livewire\Auth;

use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Illuminate\Validation\Rules;
use App\Livewire\Auth\BaseComponent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class NewPassword extends BaseComponent
{
    use Toast;

    #[Url]
    public ?string $email;
    public $password;
    public $password_confirmation;
    public $token;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()->min(6)],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $this->warning(
                'Password reset successfully',
                __($status),
                redirectTo: route('login')
            );
            return;
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.new-password');
    }
}