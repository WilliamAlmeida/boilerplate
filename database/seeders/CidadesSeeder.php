<?php

namespace Database\Seeders;

use App\Models\Cidades;
use App\Models\Estados;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cidades = Storage::disk('custom')->get('assets/cidades.json');
        $cidades = collect(json_decode($cidades));

        $estados = Estados::pluck('id', 'uf')->all();

        if(!empty($estados)) {
            foreach ($cidades->chunk(100) as $cidade) {

                $cidade = $cidade->map(function($item) use ($estados) {
                    if(isset($estados[$item[2]])) {
                        return [
                            'nome' => $item[0],
                            'codigo' => $item[1],
                            'estado_id' => $estados[$item[2]],
                        ];
                    }
                })->filter(fn($item) => !empty($item));

                Cidades::insert($cidade->all());
            }
        }
    }
}
