<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionEdit extends Component
{
    use Toast;

    #[Locked]
    public $permission;

    public bool $showDrawer2 = false;
    
    #[Validate('required|min:3', as:'permissão')]
    public $name;

    public $selected = [];

    #[Locked]
    public $roles = [];
    
    public function mount()
    {
        $this->roles = Role::orderBy('name')->get(['id', 'name'])->toArray();
    }

    #[On('edit')]
    public function edit($id = null)
    {
        $this->permission = Permission::with('roles')->find($id);

        if(!$this->permission) return;

        $this->name = $this->permission->name;
        $this->selected = $this->permission->roles->pluck('id')->all();

        $this->resetValidation();
        $this->showDrawer2 = true;
    }

    public function update()
    {
        $validated = $this->validate([
            "name" => "required|min:3|unique:permissions,name,{$this->permission->id}",
        ]);

        try {
            $this->permission->update($validated);
            $this->permission->syncRoles($this->selected);

            $this->showDrawer2 = false;
            $this->success('Permissão atualizada com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            $this->error('Erro', $th->getMessage());
        }
    }

    private function getRoles()
    {
        cache()->forget('list.roles');

        return cache()->remember('list.roles', 60 * 2, function() {
            $roles = Role::orderBy('name')->get(['id', 'name']);

            return $roles->toArray();
        });
    }

    public function render()
    {
        return view('livewire.admin.permissions.permission-edit');
    }
}
