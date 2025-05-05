<?php

namespace App\Livewire\Admin\Clientes;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Clientes;
use Livewire\Attributes\On;
use App\Livewire\Forms\Admin\FormCliente;

class ClientesEdit extends Component
{
    use Toast;

    public bool $showDrawer2 = false;
    public FormCliente $form;
    public $cliente;

    public function mount()
    {
    }

    #[On('edit')]
    public function edit($id = null)
    {
        $this->cliente = Clientes::withTrashed()->find($id);

        if(!$this->cliente) return;

        $this->form->fill($this->cliente->toArray());
        $this->reset('showDrawer2');
        $this->form->resetValidation();
        $this->showDrawer2 = true;
    }

    public function update()
    {
        $this->form->validate([
            'nome' => ['required', 'min:3', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
            'phone_1' => ['nullable', 'max:20'],
            'phone_2' => ['nullable', 'max:20'],
            'phone_3' => ['nullable', 'max:20'],
        ], attributes: [
            'nome' => 'nome',
            'email' => 'e-mail',
            'data_nascimento' => 'data de nascimento',
        ]);

        try {
            $this->cliente->update($this->form->all());

            $this->form->reset();
            $this->showDrawer2 = false;
            $this->success('Cliente atualizado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function render()
    {
        return view('livewire.admin.clientes.clientes-edit');
    }
}
