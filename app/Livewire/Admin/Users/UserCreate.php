<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Livewire\Forms\Admin\FormUser;

class UserCreate extends Component
{
    use Toast;

    public bool $showDrawer2 = false;
    public FormUser $form;

    #[On('create')]
    public function create()
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->showDrawer2 = true;
    }

    public function save()
    {
        $data = $this->form->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', Rules\Password::defaults()->min(6)],
        ], attributes: [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
        ]);

        try {
            $data['password'] = Hash::make($this->form->password);
            $data['name'] = Str::slug($this->form->name, '.');

            User::create($this->form->all());

            $this->form->reset();
            $this->showDrawer2 = false;
            $this->success('Usuário criado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            $this->error('Error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-create');
    }
}