<?php

namespace App\Models;

use App\Casts\ArrayString;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends BaseModel
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'clientes';
    protected $connection = 'supabase';

    protected $fillable = [
        'nome',
        'data_cadastro',
        'tags_personalidade',
        'data_nascimento',
        'email',
        'phone_1',
        'phone_2',
        'phone_3',
    ];

    protected $casts = [
        'data_cadastro' => 'datetime',
        'data_nascimento' => 'date',
        'tags_personalidade' => ArrayString::class,
    ];

    public function scopeSearch($query, $term)
    {
        return $query->where('nome', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }
}