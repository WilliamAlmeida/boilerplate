<?php

namespace App\Livewire\Admin\Financiamentos;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Financiamentos;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Traits\PermissionTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class FinanciamentosIndex extends Component
{
    use Toast;
    use PermissionTrait;
    use WithPagination;
    private $resource = 'financiamentos';

    public $perPage = 20;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'cliente', 'direction' => 'asc'];

    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    #[On('delete')]
    public function delete($id): void
    {
        $model = Financiamentos::withTrashed()->find($id);

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
            ['key' => 'cliente', 'label' => 'Cliente', 'format' => fn ($value) => Str::limit($value->cliente, 50)],
            ['key' => 'cpf', 'label' => 'CPF', 'sortable' => true],
            ['key' => 'banco_perfil', 'label' => 'Banco/Perfil', 'sortable' => true],
            ['key' => 'produto', 'label' => 'Produto', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'pmt', 'label' => 'PMT', 'sortable' => true],
            ['key' => 'financiado', 'label' => 'Financiado', 'sortable' => true],
            ['key' => 'vendedor', 'label' => 'Vendedor', 'sortable' => true],
            ['key' => 'data', 'label' => 'Data', 'sortable' => true, 'format' => fn ($value) => $value->data ? date('d/m/Y', strtotime($value->data)) : ''],
            ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn ($value) => $value->created_at->format('d/m/Y - H:i')],
            ['key' => 'updated_at', 'label' => 'Atualizado em', 'format' => fn ($value) => $value->updated_at->diffForHumans()],
            ['key' => 'deleted_at', 'label' => 'Status', 'hidden' => !$this->permissions(['delete'])->delete],
        ];
    }

    #[On('table:refresh')]
    public function financiamentos(): LengthAwarePaginator
    {
        return Financiamentos::query()
            ->withTrashed()
            ->sortBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->search($this->search);
            })
            ->paginate($this->perPage);
    }

    public function toggleDelete($id): void
    {
        $financiamento = Financiamentos::withTrashed()->find($id);

        if ($financiamento) {
            $financiamento->trashed() ? $financiamento->restore() : $financiamento->delete();

            $this->success('Financiamento atualizado.', position: 'toast-bottom');
        } else {
            $this->error('Financiamento não encontrado.', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.financiamentos.financiamentos-index', [
            'financiamentos' => $this->financiamentos(),
            'headers' => $this->headers(),
            'can' => $this->permissions(),
        ]);
    }
}
