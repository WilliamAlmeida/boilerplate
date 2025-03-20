<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientesEmails extends Model
{
	protected $table = 'clientes_emails';

    protected $fillable = [
    	'cliente_id', 'tipo', 'email'
    ];

    public function cliente()
    {
    	return $this->belongsTo(Clientes::class, 'cliente_id', 'id');
    }
}