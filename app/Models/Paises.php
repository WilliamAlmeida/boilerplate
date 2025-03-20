<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    protected $fillable = [
        'codigo', 'nome', 'sigla'
    ];

    public $timestamps = false;

    public function estados()
    {
        return $this->hasMany(Estados::class);
    }
}