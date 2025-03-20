<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\HelperActions;
use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleCreate extends Component
{
    use Toast;
    use HelperActions;

    public bool $showDrawer = false;
 
    #[Validate('required|min:3|unique:roles,name', as:'função')]
    public $name = '';

    public $selected = [];

    public $permissions = [];

    public function mount()
    {
        $this->permissions = $this->getPermissions();
    }

    #[On('create')]
    public function create(): void
    {
        $this->resetValidation();
        $this->reset('name', 'selected');
        $this->showDrawer = true;
    }
    
    public function save()
    {
        $validated = $this->validate();

        try {
            $role = Role::create($validated);
            $role->syncPermissions($this->selected);
            
            $this->showDrawer = false;
            $this->success('Função criada com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');
            
        } catch (\Throwable $th) {
            $this->error('Falha na criação!', $th->getMessage());
        }
    }

    private function getPermissions()
    {
        return cache()->remember('list.permissions', 60 * 2, function() {
            $permissions = Permission::orderBy('name')->get(['id', 'name']);

            $permissions = $permissions->mapToGroups(function($item, $key) {
                return [substr($item['name'], 0, strpos($item['name'], '.')) => $item->toArray()];
            });

            return $permissions->toArray();
        });
    }

    public function render()
    {
        return view('livewire.admin.roles.role-create');
    }
}
