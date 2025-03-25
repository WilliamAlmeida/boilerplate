<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendedores extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function contratos()
    {
        return $this->hasMany(Contratos::class, 'vendedor_id', 'id');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('nome', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }
}
