<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendedores;

class VendedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendedores::query()->forceDelete();

        $vendedores = ['Loja', 'Gisele', 'Kelly', 'Samara', 'Fábio'];

        foreach ($vendedores as $vendedor) {
            Vendedores::create([
                'nome' => $vendedor,
            ]);
        }
    }
}
