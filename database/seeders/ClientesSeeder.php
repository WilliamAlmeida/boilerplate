<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clientes;
use App\Models\ClientesEmails;
use App\Models\ClientesNumeros;
use Carbon\Carbon;

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

        // Create the client
        $client = Clientes::create([
            'tipo' => 'Físico',
            'nome_fantasia' => 'William',
            'cpf' => '417.544.948-54',
            'cnpj' => null,
            'razao' => 'William',
            'estado_id' => 26,
            'cidade_id' => 5114,
            'cep' => '12.610-185',
            'endereco' => 'Rua Professora Therezinha De P Ferrari Andrade',
            'bairro' => 'Vila Dos Comerciários Ii',
        ]);

        // Create an email for the client
        $client->emails()->create([
            'tipo' => 'pessoal',
            'email' => 'william@example.com',
        ]);

        // Create a phone number for the client
        $client->numeros()->create([
            'tipo' => 'celular',
            'numero' => '(12) 99999-8888',
        ]);
    }
}
