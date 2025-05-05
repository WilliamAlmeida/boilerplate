<?php

namespace App\Livewire\Admin\Clientes;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use Livewire\Attributes\On;
use App\Enums\EnumTipoCliente;
use App\Livewire\Forms\Admin\FormCliente;

class ClientesCreate extends Component
{
    use Toast;

    public bool $showDrawer = false;
    public FormCliente $form;

    public $tipos_tags = [
        ['id' => 'ansioso', 'name' => 'Ansioso'],
        ['id' => 'calmo', 'name' => 'Calmo'],
        ['id' => 'detalhista', 'name' => 'Detalhista'],
        ['id' => 'objetivo', 'name' => 'Objetivo'],
        ['id' => 'preocupado', 'name' => 'Preocupado'],
        ['id' => 'economico', 'name' => 'Econômico'],
    ];

    public function mount()
    {
    }

    #[On('create')]
    public function create()
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->reset('showDrawer');
        $this->showDrawer = true;
    }

    public function save()
    {
        $this->form->validate([
            'nome' => ['required', 'min:3', 'max:255'],
            'nome_fantasia' => ['nullable', 'min:3', 'max:255', 'unique:clientes,nome_fantasia'],
            'tipo' => ['required', 'in:' . implode(',', array_keys(EnumTipoCliente::labels()))],
            'cpf' => ['nullable', 'min:14', 'max:14', 'unique:clientes,cpf', 'required_if:tipo,' . EnumTipoCliente::FISICO->value],
            'cnpj' => ['nullable', 'min:18', 'max:18', 'unique:clientes,cnpj', 'required_if:tipo,' . EnumTipoCliente::JURIDICO->value],
            'email' => ['nullable', 'email', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
            'phone_1' => ['nullable', 'max:20'],
            'phone_2' => ['nullable', 'max:20'],
            'phone_3' => ['nullable', 'max:20'],
        ], attributes: [
            'nome' => 'nome',
            'nome_fantasia' => 'nome fantasia',
            'email' => 'e-mail',
            'data_nascimento' => 'data de nascimento',
        ]);

        try {
            Clientes::create($this->form->all());
            
            $this->form->reset();
            $this->showDrawer = false;
            $this->success('Cliente cadastrado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function render()
    {
        return view('livewire.admin.clientes.clientes-create');
    }
}
