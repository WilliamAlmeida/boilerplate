<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Spatie\Permission\Models\Role;

class UserRoles extends Component
{
    use Toast;

    public bool $showModal = false;

    #[Locked]
    public $editmode = false;
    #[Locked]
    public $user;
    
    #[Locked]
    public $roles = [];

    public array $selectedRoles = [];

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get(['id', 'name'])->toArray();
    }

    #[On('edit-roles')]
    public function editRoles($id = null)
    {
        $this->user = User::with('roles')->find($id);

        if (!$this->user) {
            $this->error('Erro', 'Usuário não encontrado');
            return;
        }

        $this->selectedRoles = $this->user->roles->pluck('id')->toArray();
        $this->editmode = auth()->user()->can('roles.edit');
        $this->showModal = true;
    }

    public function update()
    {
        try {
            if (!$this->user) {
                $this->error('Erro', 'Usuário não encontrado');
                return;
            }

            // Sync user roles
            $this->user->syncRoles($this->selectedRoles);
            
            $this->success('Funções do usuário atualizadas com sucesso.', position: 'toast-bottom');
            $this->showModal = false;
            $this->dispatch('table:refresh');
            
        } catch (\Throwable $th) {
            $this->error('Erro', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-roles');
    }
}