<?php

namespace App\Livewire\Admin\Contratos;

use App\Enums\EnumContratoStatus;
use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use App\Models\Contratos;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Traits\PermissionTrait;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

#[Title('Contratos')]
class ContratosIndex extends Component
{
    use Toast;
    use PermissionTrait;
    use WithPagination;
    private $resource = 'contratos';

    public $perPage = 20;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'data_inclusao', 'direction' => 'desc'];
    
    public array $filter = [
        'cliente' => null,
        'cpf' => null,
        'vendedor' => null,
        'data_i' => null,
        'data_e' => null,
        'status' => [],
    ];

    public Collection $clientesSearchable;

    public $arr_status_filter = [];

    public function mount()
    {
        $this->searchClients();

        $this->arr_status_filter = collect(EnumContratoStatus::cases())->map(fn($item) => ['id' => $item->value, 'name' => $item->label(),])->toArray();
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
        $model = Contratos::withTrashed()->find($id);

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
            // ['key' => 'pmt', 'label' => 'PMT', 'sortable' => true, 'format' => fn ($value) => 'R$ '.money($value->pmt)],
            ['key' => 'prazo', 'label' => 'Prazo', 'sortable' => true],
            // ['key' => 'taxa_original', 'label' => 'Taxa Original', 'sortable' => true, 'format' => fn ($value) => $value->taxa_original.'%'],
            ['key' => 'producao', 'label' => 'Produção', 'sortable' => true, 'format' => fn ($value) => 'R$ '.money($value->producao), 'hidden' => !auth()->user()->can('gerente')],
            // ['key' => 'troco_cli', 'label' => 'Troco Cliente', 'sortable' => true, 'format' => fn ($value) => 'R$ '.money($value->troco_cli)],
            ['key' => 'saldo_devedor', 'label' => 'Saldo Devedor', 'sortable' => true, 'format' => fn ($value) => 'R$ '.money($value->saldo_devedor)],
            ['key' => 'vendedor', 'label' => 'Vendedor', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'data_inclusao', 'label' => 'Data Inclusão', 'sortable' => true, 'format' => fn ($value) => $value->data_inclusao ? date('d/m/Y', strtotime($value->data_inclusao)) : ''],
            
            // ['key' => 'cpf', 'label' => 'CPF', 'sortable' => true],
            // ['key' => 'financiado', 'label' => 'Financiado', 'sortable' => true],
            // ['key' => 'produto', 'label' => 'Produto', 'sortable' => true],
            // ['key' => 'telefone', 'label' => 'Telefone', 'sortable' => true],
            // ['key' => 'obs_1', 'label' => 'Obs 1', 'sortable' => true],
            // ['key' => 'obs_2', 'label' => 'Obs 2', 'sortable' => true],
            // ['key' => 'tabela', 'label' => 'Tabela', 'sortable' => true],
            // ['key' => 'banco_perfil', 'label' => 'Banco/Perfil', 'sortable' => true],
            // ['key' => 'pos_venda', 'label' => 'Pós Venda', 'sortable' => true],
            // ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn ($value) => $value->created_at->format('d/m/Y - H:i')],
            // ['key' => 'updated_at', 'label' => 'Atualizado em', 'format' => fn ($value) => $value->updated_at->diffForHumans()],
            ['key' => 'deleted_at', 'label' => 'Ativo', 'hidden' => !$this->permissions(['delete'])->delete],
        ];
    }

    #[On('table:refresh')]
    public function contratos(): LengthAwarePaginator
    {
        return Contratos::query()
            ->withTrashed()
            ->sortBy($this->sortBy['column'], $this->sortBy['direction'])
        
            ->when($this->filter['cliente'], fn ($query) => $query->where('cliente_id', $this->filter['cliente']))
            ->when($this->filter['vendedor'], fn ($query) => $query->where('vendedor', 'like', "%{$this->filter['vendedor']}%"))
            ->when($this->filter['cpf'], fn ($query) => $query->where('cpf', 'like', "%{$this->filter['cpf']}%"))
            ->when($this->filter['data_i'], fn ($query) => $query->where('data_inclusao', '>=', $this->filter['data_i']))
            ->when($this->filter['data_e'], fn ($query) => $query->where('data_inclusao', '<=', $this->filter['data_e']))
            ->when($this->filter['status'], fn ($query) => $query->whereIn('status', $this->filter['status']))

            ->when($this->search, function ($query) {
                return $query->search($this->search);
            })
            ->paginate($this->perPage);
    }

    public function toggleDelete($id): void
    {
        $contrato = Contratos::withTrashed()->find($id);

        if ($contrato) {
            $contrato->trashed() ? $contrato->restore() : $contrato->delete();

            $this->success('Contrato atualizado.', position: 'toast-bottom');
        } else {
            $this->error('Contrato não encontrado.', position: 'toast-bottom');
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
        return view('livewire.admin.contratos.contratos-index', [
            'contratos' => $this->contratos(),
            'headers' => $this->headers(),
            'can' => $this->permissions(),
        ]);
    }
}
