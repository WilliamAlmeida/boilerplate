<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Form;

class FormCliente extends Form
{
    public ?string $nome = '';
    public $data_cadastro = null;
    public $tags_personalidade = [];
    public $data_nascimento = null;
    public ?string $email = '';
    public ?string $phone_1 = '';
    public ?string $phone_2 = '';
    public ?string $phone_3 = '';
}
