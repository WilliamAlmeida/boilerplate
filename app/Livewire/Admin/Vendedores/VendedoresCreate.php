<?php

namespace App\Livewire\Admin\Vendedores;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Vendedores;
use Livewire\Attributes\On;
use App\Livewire\Forms\Admin\FormVendedor;

class VendedoresCreate extends Component
{
    use Toast;

    public bool $showDrawer = false;
    public FormVendedor $form;

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
            'nome' => ['required', 'min:3', 'max:255', 'unique:vendedores,nome'],
        ]);

        try {
            $vendedor = Vendedores::create($this->form->all());

            $this->form->reset();
            $this->showDrawer = false;
            $this->success('Vendedor cadastrado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function render()
    {
        return view('livewire.admin.vendedores.vendedores-create');
    }
}
