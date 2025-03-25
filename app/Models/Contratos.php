<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contratos extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cpf',
        'cliente',
        'cliente_id',
        'pmt',
        'prazo',
        'taxa_original',
        'saldo_devedor',
        'producao',
        'troco_cli',
        'pos_venda',
        'vendedor',
        'vendedor_id',
        'data_inclusao',
    ];

    public function clientes()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id', 'id')->withTrashed();
    }

    public function vendedores()
    {
        return $this->belongsTo(Vendedores::class, 'vendedor_id', 'id')->withTrashed();
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('cliente', 'like', "%{$term}%")
                    ->orWhere('cpf', 'like', "%{$term}%")
                    ->orWhere('vendedor', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }
}
