<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Form;
use Livewire\Attributes\Validate;

class FormFinanciamento extends Form
{
    #[Validate('nullable|string|max:20')]
    public ?string $telefone = null;

    #[Validate('required|string|max:50', message: 'O banco/perfil é obrigatório')]
    public ?string $banco_perfil = null;

    #[Validate('required|string|max:50')]
    public ?string $produto = null;

    #[Validate('nullable|numeric|decimal:0,2')]
    public ?float $tabela = null;

    #[Validate('required|string|max:20')]
    public ?string $status = null;

    #[Validate('nullable|string|max:14')]
    public ?string $cpf = null;

    #[Validate('nullable|string|max:100')]
    public ?string $cliente = null;

    #[Validate('required')]
    public ?int $cliente_id = null;

    #[Validate('nullable|numeric|decimal:0,2')]
    public ?float $pmt = null;

    #[Validate('nullable|numeric|decimal:0,2')]
    public ?float $financiado = null;

    #[Validate('nullable|numeric|decimal:0,2')]
    public ?float $producao = null;

    #[Validate('nullable|string|max:100')]
    public ?string $vendedor = null;

    #[Validate('required|date')]
    public ?string $data = null;

    #[Validate('nullable')]
    public ?string $obs = null;
}
