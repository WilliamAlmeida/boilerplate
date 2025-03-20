<?php

namespace App\Livewire\Admin\Permissions;

use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Traits\PermissionTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionIndex extends Component
{
    use Toast;
    use PermissionTrait;
    use WithPagination;
    private $resource = 'permissions';

    public $perPage = 20;

    public string $search = '';
    public $role_id = '';

    public bool $drawer = false;
    public bool $drawer2 = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public $selected = [];

    public function clear(): void
    {
        $this->reset(['search', 'role_id']);
        $this->success('Filtros limpos.', position: 'toast-bottom');
    }

    #[On('delete')]
    public function delete($id): void
    {
        $permission = Permission::find($id);

        if (!$permission) {
            $this->error('Permissão não encontrada.', position: 'toast-bottom');
            return;
        }

        try {
            $permission->delete();
            $this->success('Permissão deletada.', position: 'toast-bottom');
        } catch (\Exception $e) {
            $this->error('Não foi possível deletar a permissão.', position: 'toast-bottom');
        }
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nome'],
            // ['key' => 'guard_name', 'label' => 'Guard'],
            ['key' => 'users_count', 'label' => 'Usuários', 'sortable' => true],
            ['key' => 'roles_count', 'label' => 'Funções', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn($value) => $value->created_at?->format('d/m/Y - H:i')],
        ];
    }

    private function getRoles()
    {
        return Role::all()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
            ];
        })->toArray();
    }

    #[On('table:refresh')]
    public function getPermissions(): LengthAwarePaginator
    {
        return Permission::query()
            ->withCount('users', 'roles')
            ->when($this->sortBy, function ($query) {
                return $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                return $query->where(function($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('guard_name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->role_id, function ($query) {
                return $query->whereHas('roles', function ($q) {
                    $q->where('id', $this->role_id);
                });
            })
            ->paginate($this->perPage);
    }

    public function bulk_delete()
    {
        if (empty($this->selected)) {
            $this->error('Nenhuma permissão selecionada.', position: 'toast-bottom');
            return;
        }

        try {
            Permission::destroy($this->selected);
            $this->selected = [];
            $this->success('Permissões deletadas.', position: 'toast-bottom');

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $this->reset('selected', 'drawer2');
        } catch (\Exception $e) {
            $this->error('Não foi possível deletar as permissões.', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.permissions.permission-index', [
            'permissions' => $this->getPermissions(),
            'headers' => $this->headers(),
            'roles' => $this->getRoles(),
            'can' => $this->permissions(),
        ]);
    }
}
