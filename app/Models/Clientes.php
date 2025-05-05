<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'tipo',
        'nome_fantasia',
        'cpf',
        'cnpj',
        'razao',
        'estado_id',
        'cidade_id',
        'cep',
        'endereco',
        'bairro',
    ];

    public function estado()
    {
        return $this->hasOne(Estados::class, 'id', 'estado_id');
    }

    public function cidade()
    {
        return $this->hasOne(Cidades::class, 'id', 'cidade_id');
    }

    public function emails()
    {
        return $this->hasMany(ClientesEmails::class, 'cliente_id', 'id');
    }

    public function numeros()
    {
        return $this->hasMany(ClientesNumeros::class, 'cliente_id', 'id');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('nome_fantasia', 'like', "%{$term}%")->orWhere('cnpj', 'like', "%{$term}%")->orWhere('cpf', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }
}