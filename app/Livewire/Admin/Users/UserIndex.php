<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use App\Traits\PermissionTrait;
use Illuminate\Support\Collection;

class UserIndex extends Component
{
    use Toast;
    private $resource = 'usuarios';
    use PermissionTrait;

    public string $search = '';

    #[Url('user')]
    public int $search_user = 0;

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    #[On('delete')]
    public function delete($id): void
    {
        $model = User::withTrashed()->find($id);

        if(!$model->trashed()) {
            $this->warning('Desative o registro antes de deletar.', position: 'toast-top');
            return;
        }

        if($model->type >= auth()->user()->type) {
            $this->warning('Você não tem permissão para deletar este registro.', position: 'toast-bottom');
            return;
        }

        $model->forceDelete();

        $this->success('Registro deletado.', position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'E-mail'],
            ['key' => 'type', 'label' => 'Tipo', 'format' => fn ($value) => $value->getTypeUser()],
            ['key' => 'roles', 'label' => 'Funções', 'sortable' => false],
            ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn ($value) => $value->created_at->format('d/m/Y - H:i')],
            ['key' => 'updated_at', 'label' => 'Atualizado em', 'format' => fn ($value) => $value->updated_at->diffForHumans()],
            ['key' => 'deleted_at', 'label' => 'Ativo', 'hidden' => !$this->permissions(['delete'])->delete],
        ];
    }

    #[On('table:refresh')]
    public function users(): Collection
    {
        return User::query()
            ->withTrashed()
            ->with('roles')
            ->sortBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', "%{$this->search}%")
                             ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->when($this->search_user, fn($query) => $query->where('id', $this->search_user))
            ->get();
    }

    public function toggleDelete($id): void
    {
        $user = User::withTrashed()->find($id);

        if($user->type >= auth()->user()->type) {
            $this->warning('Você não tem permissão para deletar este registro.', position: 'toast-bottom');
            return;
        }

        if ($user) {
            $user->trashed() ? $user->restore() : $user->delete();

            $this->success('Usuário atualizado.', position: 'toast-bottom');
        } else {
            $this->error('Usuário não encontrado.', position: 'toast-bottom');
        }
    }

    public function toggleVerify($id): void
    {
        $user = User::withTrashed()->find($id);

        if ($user) {
            if(!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            } else {
                $user->update(['email_verified_at' => null]);
            }

            $this->success('Usuário atualizado.', position: 'toast-bottom');
        } else {
            $this->error('Usuário não encontrado.', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-index', [
            'users' => $this->users(),
            'headers' => $this->headers(),
            'can' => $this->permissions(extra: ['edit_permissions' => $this->resource.'.edit_permissions']),
        ]);
    }
}