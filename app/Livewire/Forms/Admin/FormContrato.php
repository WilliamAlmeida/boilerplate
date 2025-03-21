<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Form;

class FormContrato extends Form
{
    public $cliente_id = null;
    public $cliente = '';
    public $cpf = '';

    public $pmt = 0;
    public $prazo = 0;
    public $taxa_original = 0;
    public $saldo_devedor = 0;
    public $producao = 0;
    public $troco_cli = 0;
    public $pos_venda = '';

    public $vendedor = '';
    public $data_inclusao = '';

    public function rules()
    {
        return [
            'cpf' => ['required', 'string', 'max:14'],
            'cliente' => ['required', 'string', 'max:100'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'pmt' => ['required', 'numeric', 'min:0'],
            'prazo' => ['required', 'integer', 'min:1'],
            'taxa_original' => ['required', 'numeric', 'min:0'],
            'saldo_devedor' => ['required', 'numeric', 'min:0'],
            'producao' => ['required', 'numeric', 'min:0'],
            'troco_cli' => ['nullable', 'numeric', 'min:0'],
            'pos_venda' => ['nullable', 'string', 'max:50'],
            'vendedor' => ['required', 'string', 'max:100'],
            'data_inclusao' => ['required', 'date'],
        ];
    }
}
