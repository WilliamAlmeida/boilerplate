<?php

namespace App\Livewire\Admin\Clientes;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use Livewire\Attributes\On;
use App\Traits\HelperQueries;
use App\Enums\EnumTipoCliente;
use App\Livewire\Forms\Admin\FormCliente;
use App\Http\Controllers\Api\CepController;

class ClientesCreate extends Component
{
    use Toast;
    use HelperQueries;

    public bool $showDrawer = false;
    public FormCliente $form;
    public $array_estados = [];
    public $array_cidades = [];
    public $tipos;

    public $selectedTab = 'info-tab';

    public $tipos_numeros = [
        ['id' => 'r','name' => 'Residencial'],
        ['id' => 'c','name' => 'Celular'],
        ['id' => 'l','name' => 'Comercial'],
    ];
    public $tipos_emails = [
        ['id' => '', 'name' => 'Comum'],
    ];

    public function mount()
    {
        $this->tipos = collect(EnumTipoCliente::labels())->map(fn ($tipo, $key) => ['id' => $key, 'name' => $tipo])->sortBy('name')->values();
        $this->array_estados = $this->array_estados();
    }

    #[On('create')]
    public function create()
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->reset('showDrawer', 'selectedTab');
        $this->showDrawer = true;
    }

    public function save()
    {
        $this->form->validate([
            'nome_fantasia' => ['required', 'min:3', 'max:255', 'unique:clientes,nome_fantasia'],
            'tipo' => ['required', 'in:' . implode(',', array_keys(EnumTipoCliente::labels()))],
            'cpf' => ['nullable', 'min:14', 'max:14', 'unique:clientes,cpf', 'required_if:tipo,' . EnumTipoCliente::FISICO->value],
            'cnpj' => ['nullable', 'min:18', 'max:18', 'unique:clientes,cnpj', 'required_if:tipo,' . EnumTipoCliente::JURIDICO->value],
        ], attributes: [
            'nome_fantasia' => 'nome fantasia',
        ]);

        try {
            if($this->form->tipo == EnumTipoCliente::FISICO->value) {
                $this->form->razao = $this->form->nome_fantasia;
                $this->form->cnpj = null;
            }else{
                $this->form->cpf = null;
            }

            $cliente = Clientes::create($this->form->all());

            // Create emails
            foreach ($this->form->emails ?? [] as $email) {
                $cliente->emails()->create([
                    'tipo' => $email['tipo'],
                    'email' => $email['email']
                ]);
            }

            // Create números
            foreach ($this->form->numeros ?? [] as $numero) {
                $cliente->numeros()->create([
                    'tipo' => $numero['tipo'],
                    'numero' => $numero['numero']
                ]);
            }

            $this->form->reset();
            $this->showDrawer = false;
            $this->success('Cliente cadastrado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function pesquisar_cep()
    {
        $cep = preg_replace( '/[^0-9]/', '', $this->form->cep);

        if(empty($cep)) {
            return;
        }

        $helper = new CepController;
        $response = json_decode($helper->show($cep));

        if($response->status == 'ERROR') {
            $this->error('Error', $response->message);
            return;
        }

        $this->form->fillCep($response);

        $this->success('Cep Encontrado', "Busca pelo CEP {$this->form->cep} foi finalizada!", position: 'toast-bottom');

        $this->form->resetValidation();
    }

    public function updatedFormEstadoId($value)
    {
        if($value) {
            $this->array_cidades = collect($this->array_cidades(['estado_id' => $value]));
        }else{
            $this->array_cidades = [];
        }
        $this->form->cidade_id = null;
    }

    public function render()
    {
        return view('livewire.admin.clientes.clientes-create');
    }
}
