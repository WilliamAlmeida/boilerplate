<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientesNumeros extends Model
{
	protected $table = 'clientes_numeros';

    protected $fillable = [
    	'cliente_id', 'tipo', 'numero'
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id', 'id');
    }
}