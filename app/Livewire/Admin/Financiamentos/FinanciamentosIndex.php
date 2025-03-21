<?php

namespace App\Livewire\Admin\Financiamentos;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Financiamentos;
use Livewire\Attributes\Title;
use App\Traits\PermissionTrait;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

#[Title('Financiamentos')]
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
    
    public array $filter = [
        'cliente' => null,
        'cpf' => null,
        'banco_perfil' => null,
        'vendedor' => null,
        'status' => null,
        'data_i' => null,
        'data_e' => null,
    ];

    public Collection $clientesSearchable;

    public function mount()
    {
        $this->searchClients();
    }

    public function clear(): void
    {
        $this->reset();
        $this->searchClients();
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
            
            ->when($this->filter['cliente'], fn ($query) => $query->where('cliente_id', $this->filter['cliente']))
            ->when($this->filter['banco_perfil'], fn ($query) => $query->where('banco_perfil', 'like', "%{$this->filter['banco_perfil']}%"))
            ->when($this->filter['vendedor'], fn ($query) => $query->where('vendedor', 'like', "%{$this->filter['vendedor']}%"))
            ->when($this->filter['cpf'], fn ($query) => $query->where('cpf', 'like', "%{$this->filter['cpf']}%"))
            ->when($this->filter['status'], fn ($query) => $query->where('status', 'like', "%{$this->filter['status']}%"))
            ->when($this->filter['data_i'], fn ($query) => $query->where('data', '>=', $this->filter['data_i']))
            ->when($this->filter['data_e'], fn ($query) => $query->where('data', '<=', $this->filter['data_e']))
            
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
    
    public function searchClients(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Clientes::select('id', 'nome_fantasia')->where('id', $this->filter['cliente'])->toBase()->get();

        $this->clientesSearchable = Clientes::query()
            ->select('id', 'nome_fantasia')
            ->where('nome_fantasia', 'like', "%$value%")
            ->orWhere('cpf', 'like', "%$value%")
            ->orWhere('cnpj', 'like', "%$value%")
            ->take(5)
            ->orderBy('nome_fantasia')
            ->toBase()
            ->get()
            ->merge($selectedOption);
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
