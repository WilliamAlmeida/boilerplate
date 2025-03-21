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

    public array $sortBy = ['column' => 'nome_fantasia', 'direction' => 'asc'];

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
            ['key' => 'nome_fantasia', 'label' => 'Nome Fantasia', 'format' => fn ($value) => Str::limit($value->nome_fantasia, 50)],
            ['key' => 'cpf_cnpj', 'label' => 'CPF / CNPJ', 'sortable' => false, 'format' => fn ($value) => $value->cpf ?? $value->cnpj],
            ['key' => 'tipo', 'label' => 'Tipo'],
            ['key' => 'cidade.nome', 'label' => 'Cidade / Estado', 'sortable' => false, 'format' => fn ($value) => $value->cidade?->nome . ' - ' . $value->estado?->uf],
            ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn ($value) => $value->created_at->format('d/m/Y - H:i')],
            ['key' => 'updated_at', 'label' => 'Atualizado em', 'format' => fn ($value) => $value->updated_at->diffForHumans()],
            ['key' => 'deleted_at', 'label' => 'Status', 'hidden' => !$this->permissions(['delete'])->delete],
        ];
    }

    #[On('table:refresh')]
    public function clientes(): LengthAwarePaginator
    {
        return Clientes::query()
            ->withTrashed()
            ->sortBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where('nome_fantasia', 'like', "%{$this->search}%")->orWhere('cnpj', 'like', "%{$this->search}%")->orWhere('cpf', 'like', "%{$this->search}%");
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
