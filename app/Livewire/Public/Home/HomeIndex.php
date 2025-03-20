<?php

namespace App\Livewire\Public\Home;

use Livewire\Component;
use App\Traits\DialogTrait;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.public')]
class HomeIndex extends Component
{
    use DialogTrait;

    public function dialogTest($type)
    {
        $position = match ($type) {
            'success' => 'top-right',
            'error' => 'top-left',
            'warning' => 'bottom-left',
            'info' => 'bottom-right',
            default => 'center',
        };
        $this->dialog('Aviso!', 'Operation completed successfully', confirmOptions: ['text' => 'Ok'], css: 'dialog-'.$type, position: $position);
    }

    public function showConfirmation()
    {
        $this->dialog('Confirmation', 'Are you sure you want to delete this item?', confirmOptions: ['text' => 'Yes'], cancelOptions: ['text' => 'No'], css: 'dialog-confirm');
    }

    public function render()
    {
        return view('livewire.public.home.home-index')->title(__('Home'));
    }
}
