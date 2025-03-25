<?php

namespace App\Http\Resources\ResourceContrato;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cpf' => $this->cpf,
            'cliente' => $this->cliente,
            'cliente_id' => $this->cliente_id,
            'pmt' => $this->pmt,
            'prazo' => $this->prazo,
            'taxa_original' => $this->taxa_original,
            'saldo_devedor' => $this->saldo_devedor,
            'telefone' => $this->telefone,
            'obs_1' => $this->obs_1,
            'obs_2' => $this->obs_2,
            'status' => $this->status,
            'producao' => $this->producao,
            'troco_cli' => $this->troco_cli,
            'pos_venda' => $this->pos_venda,
            'vendedor' => $this->vendedor,
            'vendedor_id' => $this->vendedor_id,
            'banco_perfil' => $this->banco_perfil,
            'produto' => $this->produto,
            'tabela' => $this->tabela,
            'financiado' => $this->financiado,
            'data_inclusao' => $this->data_inclusao,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}