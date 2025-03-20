<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Mary\Traits\Toast;
use App\Livewire\Auth\BaseComponent;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends BaseComponent
{
    use Toast;

    public $email = '';

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $user = User::withTrashed()->where('email', $this->email)->first();

        if(!$user) {
            return $this->addError('email', 'E-mail não encontrado.');
        }

        if($user->trashed()) {
            return $this->addError('email', 'E-mail não encontrado.');
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}