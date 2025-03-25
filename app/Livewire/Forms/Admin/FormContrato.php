<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Form;
use Livewire\Attributes\Validate;

class FormContrato extends Form
{
    #[Validate('required|string|max:14')]
    public ?string $cpf = '';

    #[Validate('required|string|max:100')]
    public ?string $cliente = '';

    #[Validate('nullable|exists:clientes,id')]
    public ?string $cliente_id = null;

    #[Validate('required|numeric|min:0')]
    public ?int $pmt = 0;

    #[Validate('required|integer|min:1')]
    public ?int $prazo = 0;

    #[Validate('required|numeric|min:0')]
    public ?int $taxa_original = 0;

    #[Validate('required|numeric|min:0')]
    public ?float $saldo_devedor = 0;

    #[Validate('required|numeric|min:0')]
    public ?float $producao = 0;

    #[Validate('nullable|numeric|min:0')]
    public ?float $troco_cli = 0;

    #[Validate('nullable|string|max:50')]
    public ?string $pos_venda = '';

    #[Validate('required|string|max:100')]
    public ?string $vendedor = '';

    #[Validate('nullable|exists:vendedores,id')]
    public ?int $vendedor_id = null;

    #[Validate('required|date')]
    public ?string $data_inclusao = '';

    #[Validate('nullable|string|max:20')]
    public ?string $telefone = null;

    #[Validate('required|string|max:50', message: 'O banco/perfil é obrigatório')]
    public ?string $banco_perfil = null;

    #[Validate('nullable|string|max:50')]
    public ?string $produto = null;

    #[Validate('nullable|numeric|min:0')]
    public ?float $tabela = null;

    #[Validate('required|string')]
    public ?string $status = null;

    #[Validate('nullable|numeric|min:0')]
    public ?float $financiado = null;

    #[Validate('nullable|string|max:255')]
    public ?string $obs_1 = null;

    #[Validate('nullable|string|max:255')]
    public ?string $obs_2 = null;
}