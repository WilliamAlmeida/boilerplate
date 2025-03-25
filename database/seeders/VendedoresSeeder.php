<?php

namespace Database\Seeders;

use App\Models\Vendedores;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

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
                'nome' => Str::upper($vendedor),
            ]);
        }
    }
}
