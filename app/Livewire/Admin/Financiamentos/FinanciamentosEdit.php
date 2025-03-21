<?php

namespace App\Livewire\Admin\Financiamentos;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use Livewire\Attributes\On;
use App\Models\Financiamentos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Admin\FormFinanciamento;

class FinanciamentosEdit extends Component
{
    use Toast;

    public bool $showDrawer2 = false;
    public FormFinanciamento $form;

    public $selectedTab = 'info-tab';

    public Collection $clientesSearchable;

    public ?Clientes $cliente;
    
    public ?Financiamentos $financiamento;

    public function mount()
    {
        $this->searchClients();
    }

    #[On('edit')]
    public function edit($id)
    {
        $this->financiamento = Financiamentos::withTrashed()->with('clientes')->find($id);

        if(!$this->financiamento) return;

        $this->form->fill($this->financiamento->toArray());
        $this->resetValidation();
        $this->reset('showDrawer2', 'selectedTab');
        $this->showDrawer2 = true;

        // Ensure client data is properly loaded
        $this->cliente = $this->financiamento->clientes;

        if ($this->cliente) {
            $this->form->fill([
                'cliente_id' => $this->cliente->id,
                'cliente' => $this->cliente->nome_fantasia,
                'cpf' => $this->cliente->cpf,
            ]);
        }
    }

    public function update()
    {
        $this->form->validate();

        DB::beginTransaction();

        try {
            $this->financiamento->update($this->form->all());

            $this->form->reset();
            $this->showDrawer2 = false;
            $this->success('Financiamento atualizado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            $this->error('Erro ao atualizar financiamento.', position: 'toast-bottom');
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
        return view('livewire.admin.financiamentos.financiamentos-edit');
    }
}
