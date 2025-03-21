<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financiamentos extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'telefone',
        'banco_perfil',
        'produto',
        'tabela',
        'status',
        'cpf',
        'cliente',
        'cliente_id',
        'pmt',
        'financiado',
        'producao',
        'vendedor',
        'data',
        'obs',
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id', 'id');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('cliente', 'like', "%{$term}%")
                    ->orWhere('cpf', 'like', "%{$term}%")
                    ->orWhere('banco_perfil', 'like', "%{$term}%")
                    ->orWhere('vendedor', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }
}
