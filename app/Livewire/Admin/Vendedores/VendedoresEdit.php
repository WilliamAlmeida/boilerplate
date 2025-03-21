<?php

namespace App\Livewire\Admin\Vendedores;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Vendedores;
use Livewire\Attributes\On;
use App\Livewire\Forms\Admin\FormVendedor;

class VendedoresEdit extends Component
{
    use Toast;

    public bool $showDrawer2 = false;
    public FormVendedor $form;
    public $vendedor;

    #[On('edit')]
    public function edit($id = null)
    {
        $this->vendedor = Vendedores::withTrashed()->find($id);

        if(!$this->vendedor) return;

        $this->form->fill($this->vendedor->toArray());
        $this->reset('showDrawer2');
        $this->form->resetValidation();
        $this->showDrawer2 = true;
    }

    public function update()
    {
        $this->form->validate([
            'nome' => ['required', 'min:3', 'max:255', 'unique:vendedores,nome,' . $this->vendedor->id],
        ]);

        try {
            $this->vendedor->update($this->form->all());

            $this->form->reset();
            $this->showDrawer2 = false;
            $this->success('Vendedor atualizado com sucesso.', position: 'toast-bottom');
            $this->dispatch('table:refresh');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function render()
    {
        return view('livewire.admin.vendedores.vendedores-edit');
    }
}
