<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    protected $fillable = [
        'codigo', 'nome', 'uf', 'pais_id'
    ];

    public $timestamps = false;

    public function pais()
    {
        return $this->belongsTo(Paises::class);
    }

    public function cidades()
    {
        return $this->hasMany(Cidades::class);
    }
}