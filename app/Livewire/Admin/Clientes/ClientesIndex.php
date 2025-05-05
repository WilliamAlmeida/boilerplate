<?php

namespace App\Livewire\Admin\Clientes;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Traits\PermissionTrait;
use Illuminate\Pagination\LengthAwarePaginator;

#[Title('Clientes')]
class ClientesIndex extends Component
{
    use Toast;
    use PermissionTrait;
    use WithPagination;
    private $resource = 'clientes';

    public $perPage = 20;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'nome', 'direction' => 'asc'];

    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    #[On('delete')]
    public function delete($id): void
    {
        $model = Clientes::withTrashed()->find($id);

        if(!$model->trashed()) {
            $this->warning('Desative o registro antes de deletar.', position: 'toast-top');
            return;
        }

        $model->forceDelete();

        $this->success('Registro deletado.', position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'nome', 'label' => 'Nome', 'format' => fn ($value) => Str::limit($value->nome, 50)],
            ['key' => 'email', 'label' => 'E-mail', 'format' => fn ($value) => Str::limit($value->email, 50)],
            ['key' => 'phone_1', 'label' => 'Celular', 'format' => fn ($value) => $value->phone_1],
            ['key' => 'data_nascimento', 'label' => 'Data Nascimento', 'format' => fn ($value) => $value->data_nascimento ? $value->data_nascimento->format('d/m/Y') : ''],
            ['key' => 'data_cadastro', 'label' => 'Registrado em', 'format' => fn ($value) => $value->data_cadastro ? $value->data_cadastro->format('d/m/Y - H:i') : ''],
            ['key' => 'updated_at', 'label' => 'Atualizado em', 'format' => fn ($value) => $value->updated_at ? $value->updated_at->diffForHumans() : ''],
            ['key' => 'deleted_at', 'label' => 'Ativo', 'hidden' => !$this->permissions(['delete'])->delete],
        ];
    }

    #[On('table:refresh')]
    public function clientes(): LengthAwarePaginator
    {
        return Clientes::query()
            ->withTrashed()
            ->sortBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where('nome', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone_1', 'like', "%{$this->search}%");
            })
            ->paginate($this->perPage);
    }

    public function toggleDelete($id): void
    {
        $cliente = Clientes::withTrashed()->find($id);

        if ($cliente) {
            $cliente->trashed() ? $cliente->restore() : $cliente->delete();

            $this->success('Cliente atualizado.', position: 'toast-bottom');
        } else {
            $this->error('Cliente não encontrado.', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.clientes.clientes-index', [
            'clientes' => $this->clientes(),
            'headers' => $this->headers(),
            'can' => $this->permissions(),
        ]);
    }
}
