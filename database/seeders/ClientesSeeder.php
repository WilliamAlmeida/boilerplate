<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clientes;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clientes::query()->forceDelete();

        $client = Clientes::create([
            'nome' => 'William',
            'data_cadastro' => now(),
            'tags_personalidade' => ['calmo', 'detalhista'],
            'data_nascimento' => '1990-01-15',
            'email' => 'william@example.com',
            'phone_1' => '(12) 99999-8888',
            'phone_2' => '(12) 3333-4444',
            'phone_3' => null,
        ]);

        $client2 = Clientes::create([
            'nome' => 'Farmácia Modelo',
            'data_cadastro' => now(),
            'tags_personalidade' => ['objetivo', 'economico'],
            'data_nascimento' => null,
            'email' => 'contato@farmaciamodelo.com.br',
            'phone_1' => '(12) 98765-4321',
            'phone_2' => '(12) 3456-7890',
            'phone_3' => '(12) 3456-7891',
        ]);
    }
}
