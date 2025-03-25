<?php

namespace App\Livewire\Components\Statistics;

use App\Models\Contratos;
use App\Enums\EnumContratoStatus;
use App\Models\Vendedores;
use Livewire\Component;

class ContratosStats extends Component
{
    public $vendedor_id = 0;

    public $arr_vendedores = [];

    public function mount()
    {
        if(auth()->user()->hasRole('vendedor')) {
            $this->vendedor_id = auth()->user()->vendedor->id;
        }

        $this->arr_vendedores = Vendedores::toBase()->get();
    }

    public function render()
    {
        // Get total contracts count
        $totalContratos = Contratos::
        when($this->vendedor_id, function ($query) {
            return $query->where('vendedor_id', $this->vendedor_id);
        })
        ->count();
        
        // Get this month's contracts
        $thisMonthContratos = Contratos::
        when($this->vendedor_id, function ($query) {
            return $query->where('vendedor_id', $this->vendedor_id);
        })
        ->whereMonth('data_inclusao', now()->month)
        ->whereYear('data_inclusao', now()->year)
        ->count();
        
        // Get total production value
        $totalProducao = Contratos::
        when($this->vendedor_id, function ($query) {
            return $query->where('vendedor_id', $this->vendedor_id);
        })
        ->sum('producao');
        
        // Get pending contracts
        $pendingContratos = Contratos::
        when($this->vendedor_id, function ($query) {
            return $query->where('vendedor_id', $this->vendedor_id);
        })
        ->where('status', EnumContratoStatus::PENDENTE_ASSINATURA)->count();

        $values_by_status = collect(EnumContratoStatus::labels())->map(function ($label, $value) {
            return [
                'id' => $value,
                'name' => $label,
                'count' => Contratos::when($this->vendedor_id, fn($query) => $query->where('vendedor_id', $this->vendedor_id))->where('status', $value)->count(),
            ];
        })->filter(fn($status) => $status['count'] > 0)->values();
        
        return view('livewire.components.statistics.contratos-stats', [
            'totalContratos' => $totalContratos,
            'thisMonthContratos' => $thisMonthContratos,
            'totalProducao' => number_format($totalProducao, 2, ',', '.'),
            'pendingContratos' => $pendingContratos,
            'values_by_status' => $values_by_status,
        ]);
    }
}
