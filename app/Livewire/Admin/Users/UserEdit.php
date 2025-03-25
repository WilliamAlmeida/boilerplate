<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Livewire\Forms\Admin\FormUser;
use App\Models\Vendedores;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    use Toast;

    #[Locked]
    public $editmode = false;
    #[Locked]
    public $user;

    public bool $showDrawer = false;
    public FormUser $form;

    public $newPassword = false;
    
    // Added properties for role management
    public $selected = [];
    
    #[Locked]
    public $roles = [];

    public $arr_vendedores = [];

    public $vendedor_id;

    public function mount()
    {
        // Load all roles on component mount
        $this->roles = Role::orderBy('name')->get(['id', 'name'])->toArray();
    }

    #[On('view')]
    public function view($id = null)
    {
        $this->user = User::withTrashed()->with('roles', 'vendedor')->find($id);

        if(!$this->user) return;

        $this->form->fill($this->user->toArray());

        $this->vendedor_id = $this->user->vendedor->id ?? null;

        $this->reset('editmode', 'showDrawer', 'newPassword');
        $this->form->resetValidation();
        $this->showDrawer = true;
    }

    #[On('edit')]
    public function edit($id = null)
    {
        $this->view($id);
        $this->editmode = true;

        // Load all vendedores on component mount
        $this->arr_vendedores = Vendedores::orderBy('nome')->get(['id', 'nome'])->toArray();
    }

    public function update()
    {
        $data = $this->form->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'name' => ['required', 'min:3', 'max:255', Rule::unique('users', 'name')->ignore($this->user->id)],
            'type' => ['required', 'integer', Rule::in(array_column(User::$list_type_user, 'type'))],
            'password' => ['nullable', 'min:8', 'max:255', Rules\Password::defaults()->min(6)],
        ]);

        try {
            if($this->form->password) {
                $data['password'] = Hash::make($this->form->password);
            } else {
                unset($data['password']);
            }

            $this->user->update($data);

            if($this->user->vendedor) {
                if($this->vendedor_id) {
                    if($this->vendedor_id != $this->user->vendedor->id) {
                        $this->user->vendedor->update(['user_id' => null]);

                        Vendedores::find($this->vendedor_id)->update(['user_id' => $this->user->id]);
                    }
                }else{
                    $this->user->vendedor->update(['user_id' => null]);
                }
            }else{
                if($this->vendedor_id) {
                    Vendedores::find($this->vendedor_id)->update(['user_id' => $this->user->id]);
                }
            }

            $this->form->reset();
            $this->showDrawer = false;
            $this->success('Usuário atualizado.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            $this->error('Error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-edit');
    }
}