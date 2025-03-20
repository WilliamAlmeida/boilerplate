<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Form;
use Illuminate\Support\Str;

class FormCliente extends Form
{
    public string $nome_fantasia = '';
    public string $tipo = '';
    public ?string $cpf = null;
    public ?string $cnpj = null;
    public ?string $razao = null;

    public $cidade_id;
    public $estado_id;
    public ?string $cep = '';
    public ?string $endereco = '';
    public ?string $numero = '';
    public ?string $bairro = '';
    public ?string $complemento = '';

    public $numeros = [];
    public $emails = [];

    public function fillCep($values)
    {
        $this->bairro       = Str::title($values->bairro ?: null);
        $this->complemento  = Str::title($values->complemento ?: null);
        $this->endereco     = Str::title($values->logradouro ?: null);
        $this->numero       = $values->numero ?: null;
        $this->cidade_id     = $values->cidade_id ?: null;
        $this->estado_id     = $values->estado_id ?: null;
    }
}
