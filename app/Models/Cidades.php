<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    protected $fillable = [
        'codigo', 'nome', 'estado_id'
    ];

    public $timestamps = false;

    public function estado()
    {
        return $this->belongsTo(Estados::class);
    }
}
