<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\HelperActions;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleEdit extends Component
{
    use Toast;
    use HelperActions;

    public bool $showDrawer2 = false;
    public string $selectedTab = 'permissions-tab';
 
    #[Validate('required|min:3', as:'função')]
    public $name;

    #[Locked]
    public $role;

    public $selected = [];

    public $permissions = [];

    public function mount()
    {
        $this->permissions = $this->getPermissions();
    }

    #[On('edit')]
    public function edit($id): void
    {
        $this->resetValidation();

        $this->reset('name', 'selected', 'selectedTab');

        $this->role = Role::with('permissions:id,name', 'users')->findOrFail($id);

        $this->fill($this->role->only('name'));

        // $json = $this->role->permissions->pluck('name')->transform(function($item) {
        //     return substr($item, 0, strpos($item, '.'));
        // })->unique()->values()->toJson();
        // dump($json);

        $this->selected = $this->role->permissions->pluck('id')->all();

        unset($this->role->permissions);

        $this->showDrawer2 = true;
    }
    
    public function update()
    {
        $validated = $this->validate([
            "name" => "required|min:3|unique:roles,name,{$this->role->id}",
        ]);

        try {
            $this->role->update($validated);

            $this->role->syncPermissions($this->selected);

            $this->showDrawer2 = false;
    
            $this->success('Função atualizada com sucesso.', position: 'toast-bottom');

            $this->dispatch('table:refresh');
        } catch (\Throwable $th) {
            $this->error('Falha na atualização!', $th->getMessage());
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

    public function removeUser($id)
    {
        $this->role->users()->detach($id);

        $this->role->load('users');

        $this->dispatch('table:refresh');

        $this->success('Usuário removido com sucesso.', position: 'toast-bottom');
    }

    public function render()
    {
        return view('livewire.admin.roles.role-edit');
    }
}
