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

class ContratosEdit extends Component
{
    use Toast;

    public bool $showDrawer2 = false;
    public FormContrato $form;

    public $selectedTab = 'cliente-tab';

    public Collection $clientesSearchable;

    public ?Clientes $cliente;
    
    public ?Contratos $contrato;

    public $arr_status = [];
    public $arr_vendedores = [];

    public function mount()
    {
        $this->arr_status = collect(EnumContratoStatus::cases())->map(fn($item) => ['id' => $item->value, 'name' => $item->label(),])->toArray();
        $this->arr_vendedores = Vendedores::select('id', 'nome')->get();
    }

    #[On('edit')]
    public function edit($id)
    {
        $this->contrato = Contratos::withTrashed()->with('clientes', 'vendedores')->find($id);

        if(!$this->contrato) return;

        $this->form->fill($this->contrato->toArray());

        $this->searchClients();

        $this->resetValidation();
        $this->reset('showDrawer2', 'selectedTab');
        $this->showDrawer2 = true;
    }

    public function update()
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
            $this->contrato->update($this->form->all());

            $this->form->reset();
            $this->showDrawer2 = false;
            $this->success('Contrato atualizado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            $this->error('Erro ao atualizar contrato.', position: 'toast-bottom');
        }
    }

    public function searchClients(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Clientes::select('id', 'nome_fantasia')->where('id', $this->form->cliente_id)->toBase()->get();

        $this->clientesSearchable = Clientes::query()
            ->withTrashed()
            ->select('id', 'nome_fantasia')
            ->where('nome_fantasia', 'like', "%$value%")
            ->orWhere('cpf', 'like', "%$value%")
            ->orWhere('cnpj', 'like', "%$value%")
            ->take(5)
            ->orderBy('nome_fantasia')
            ->toBase()
            ->get();

        // verifica se o cliente selecionado está na lista de clientes pesquisados
        if ($selectedOption && !$this->clientesSearchable->contains('id', $this->form->cliente_id)) {
            $this->clientesSearchable = $this->clientesSearchable->merge($selectedOption);
        }
    }

    public function updatedFormClienteId($value)
    {
        $this->cliente = Clientes::find($value);

        if ($this->cliente) {
            $this->form->fill([
                'cliente' => $this->cliente->nome_fantasia,
                'cpf' => $this->cliente->cpf,
            ]);
        }else{
            $this->form->reset(['cliente', 'cpf']);
        }
    }

    public function render()
    {
        return view('livewire.admin.contratos.contratos-edit');
    }
}
