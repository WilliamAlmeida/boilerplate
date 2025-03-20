<?php

namespace App\Livewire\Public\Account;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use App\Traits\HelperFunctions;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

#[Layout('components.layouts.public')]
#[Title('Minha Conta')]
class AccountEdit extends Component
{
    use Toast;
    use HelperFunctions;

    public $name;
    public $email;
    public $timezone;

    public $passwordForm = false;
    public $current_password;
    public $password;
    public $password_confirmation;

    #[Validate('required|current_password', as: 'password')]
    public $password_to_delete;

    public $deleteAccountModal = false;

    public $selectedTab = 'account-tab';

    public $country = 'BR';

    public function mount()
    {
        $this->fill(auth()->user()->only('name', 'email', 'timezone'));
    }

    public function cancel_validation()
    {
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->resetValidation();
    }

    public function atualizar_conta()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore(auth()->id())],
        ]);

        $this->email = Str::lower($this->email);

        $usuario = auth()->user()->fill($this->only('name', 'email', 'timezone'));

        if($usuario->isDirty('email')) {
            $usuario->email_verified_at = null;
        }

        try {
            $usuario->save();

            $this->success('Conta atualizada com sucesso!', position: 'toast-bottom');
    
            $this->mount();

        } catch (\Throwable $th) {
            // throw $th;

            $this->error('Erro ao atualizar a conta!');
        }
    }

    public function atualizar_senha()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults()->min(6), 'confirmed'],
            'password_confirmation' => ['required', Password::defaults()->min(6)]
        ]);

        try {
            auth()->user()->update([
                'password' => Hash::make($this->password),
            ]);

            $this->success('Senha atualizada com sucesso!', position: 'toast-bottom');

            $this->reset('current_password', 'password', 'password_confirmation', 'passwordForm');

        } catch (\Throwable $th) {
            // throw $th;

            $this->error('Erro ao atualizar a senha!');
        }
    }

    public function deletar_conta()
    {
        $this->validateOnly('password_to_delete');

        $usuario = auth()->user();

        auth()->logout();

        // $usuario->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect(route('home'));
    }

    #[Computed]
    public function timezones()
    {
        return $this->getTimezones($this->country);
    }

    public function render()
    {
        return view('livewire.public.account.account-edit');
    }
}
