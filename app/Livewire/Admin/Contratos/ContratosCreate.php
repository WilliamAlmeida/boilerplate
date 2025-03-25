<?php

namespace App\Livewire\Admin\Contratos;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use App\Models\Contratos;
use App\Models\Vendedores;
use Livewire\Attributes\On;
use App\Enums\EnumContratoStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Admin\FormContrato;

class ContratosCreate extends Component
{
    use Toast;

    public bool $showDrawer = false;
    public FormContrato $form;

    public $selectedTab = 'cliente-tab';

    public Collection $clientesSearchable;

    public ?Clientes $cliente;

    public $arr_status = [];
    public $arr_vendedores = [];

    public function mount()
    {
        $this->arr_status = collect(EnumContratoStatus::cases())->map(fn($item) => ['id' => $item->value, 'name' => $item->label(),])->toArray();
        $this->arr_vendedores = Vendedores::select('id', 'nome')->get();

        $this->searchClients();
    }

    #[On('create')]
    public function create()
    {
        $this->form->reset();
        $this->form->data_inclusao = now()->format('Y-m-d');
        $this->form->resetValidation();
        $this->reset('showDrawer', 'selectedTab');
        $this->showDrawer = true;
    }

    public function save()
    {
        if($this->form->vendedor_id) {
            $vendedor = Vendedores::find($this->form->vendedor_id);

            $this->form->vendedor = $vendedor->nome;
        }else{
            $this->form->vendedor = null;
        }

        $this->form->validate();

        DB::beginTransaction();

        try {
            Contratos::create($this->form->all());

            $this->form->reset();
            $this->showDrawer = false;
            $this->success('Contrato cadastrado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            $this->error('Erro ao cadastrar contrato.', position: 'toast-bottom');
        }
    }

    public function searchClients(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Clientes::select('id', 'nome_fantasia')->where('id', $this->form->cliente_id)->toBase()->get();

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

    public function updatedFormClienteId($value)
    {
        $this->cliente = Clientes::with([
            'numeros' => fn($query) => $query->take(1),
        ])->find($value);

        if ($this->cliente) {
            $this->form->fill([
                'cliente' => $this->cliente->nome_fantasia,
                'cpf' => $this->cliente->cpf,
                'telefone' => $this->cliente->numeros->first()->numero,
            ]);
        }else{
            $this->form->reset(['cliente', 'cpf', 'telefone']);
        }
    }

    public function render()
    {
        return view('livewire.admin.contratos.contratos-create');
    }
}
