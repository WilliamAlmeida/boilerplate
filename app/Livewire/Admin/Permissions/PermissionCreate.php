<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionCreate extends Component
{
    use Toast;
    
    public bool $showDrawer = false;
    
    #[Validate('required|array|min:1', as:'permissões')]
    public $permissions = [];

    public $selected = [];

    #[Locked]
    public $roles = [];
    
    public function mount()
    {
        $this->roles = Role::orderBy('name')->get(['id', 'name'])->toArray();
    }

    #[On('create')]
    public function create()
    {
        $this->reset('permissions', 'selected');
        $this->resetValidation();
        $this->showDrawer = true;
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        
        try {
            $permissionModels = [];
            
            foreach ($this->permissions as $permName) {
                $permName = Str::squish($permName);
                if (empty($permName)) continue;
                
                $permission = Permission::create([
                    'name' => $permName,
                    'guard_name' => 'web'
                ]);
                
                if (!empty($this->selected)) {
                    $permission->syncRoles($this->selected);
                }
                
                $permissionModels[] = $permission;
            }

            DB::commit();
            
            $this->showDrawer = false;
            $this->success('Permissão(ões) criada(s) com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');
            
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error('Erro', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.permissions.permission-create');
    }
}
