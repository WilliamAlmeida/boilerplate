<?php

namespace App\Livewire\Admin\Roles;

use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Traits\PermissionTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleIndex extends Component
{
    use Toast;
    use PermissionTrait;
    use WithPagination;
    private $resource = 'roles';

    public $perPage = 20;

    public string $search = '';
    public $permission_id = '';

    public bool $drawer = false;
    public bool $drawer2 = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public $selected = [];

    public function clear(): void
    {
        $this->reset(['search', 'permission_id']);
        $this->success('Filtros limpos.', position: 'toast-bottom');
    }

    #[On('delete')]
    public function delete($id): void
    {
        $role = Role::find($id);

        if (!$role) {
            $this->error('Função não encontrada.', position: 'toast-bottom');
            return;
        }

        try {
            $role->delete();
            $this->success('Função deletada.', position: 'toast-bottom');
        } catch (\Exception $e) {
            $this->error('Não foi possível deletar a função.', position: 'toast-bottom');
        }
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            // ['key' => 'used_where', 'label' => 'Uso', 'sortable' => false, 'format' => fn($value) => 'Global'],
            ['key' => 'name', 'label' => 'Nome', 'sortable' => true],
            ['key' => 'users_count', 'label' => 'Usuários', 'sortable' => true],
            ['key' => 'permissions_count', 'label' => 'Permissões', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn($value) => $value->created_at?->format('d/m/Y - H:i')],
        ];
    }

    private function getPermissions()
    {
        return Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
            ];
        })->toArray();
    }

    #[On('table:refresh')]
    public function roles(): LengthAwarePaginator
    {
        return Role::query()
            ->withCount('users', 'permissions')
            ->when($this->sortBy, function ($query) {
                return $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                return $query->where(function($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                          ->orWhere('guard_name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->permission_id, function ($query) {
                return $query->whereHas('permissions', function ($q) {
                    $q->where('id', $this->permission_id);
                });
            })
            ->paginate($this->perPage);
    }

    public function bulk_delete()
    {
        if (empty($this->selected)) {
            $this->error('Nenhuma função selecionada.', position: 'toast-bottom');
            return;
        }

        try {
            Role::destroy($this->selected);
            $this->selected = [];
            $this->success('Funções deletadas.', position: 'toast-bottom');

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $this->reset('selected', 'drawer2');
        } catch (\Exception $e) {
            $this->error('Não foi possível deletar as funções.', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.roles.role-index', [
            'roles' => $this->roles(),
            'headers' => $this->headers(),
            'permissions' => $this->getPermissions(),
            'can' => $this->permissions()
        ]);
    }
}
