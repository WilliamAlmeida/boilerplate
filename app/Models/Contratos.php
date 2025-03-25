<?php

namespace App\Models;

use App\Enums\EnumContratoStatus;
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
        'telefone',
        'banco_perfil',
        'produto',
        'tabela',
        'status',
        'financiado',
        'producao',
        'obs_1',
        'obs_2'
    ];

    protected function casts(): array
    {
        return [
            'status' => EnumContratoStatus::class
        ];
    }

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
                    ->orWhere('banco_perfil', 'like', "%{$term}%")
                    ->orWhere('vendedor', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }
}
