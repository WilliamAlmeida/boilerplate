<?php

namespace App\Traits;

use App\Traits\CacheDictionary;
use Illuminate\Support\Facades\Cache;

trait HelperQueries
{
    use CacheDictionary;

    private function array_estados(array $data = [])
    {
        $data = collect($data);

        $token = $data->get('token', 'array_estados');

        $retrieve_estados = function ($data) {
            return \App\Models\Estados::select($data->get('columns', ['id', 'uf']))->get()->toArray();
        };

        if ($data->get('no_cache')) return $retrieve_estados($data);

        $this->dictAdd($token);

        return Cache::remember($token, $data->get('cache_duration', 60*60), function() use ($retrieve_estados, $data) {
            return $retrieve_estados($data);
        });
    }

    private function array_cidades(array $data = [])
    {
        $data = collect($data);

        $token = $data->get('token', 'array_cidades');

        if (!$data->get('estado_id')) return [];

        $retrieve_cidades = function ($data) {
            return \App\Models\Cidades::select($data->get('columns', ['id', 'nome']))->whereEstadoId($data->get('estado_id'))->get()->toArray();
        };

        if ($data->get('no_cache')) return $retrieve_cidades($data);

        $this->dictAdd($token);

        return Cache::remember($token, $data->get('cache_duration', 60*60), function() use ($retrieve_cidades, $data) {
            return $retrieve_cidades($data);
        });
    }
}
