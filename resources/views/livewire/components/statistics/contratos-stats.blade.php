<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="col-span-full">
        @hasanyrole('admin')
        <x-choices-offline label="Vendedor" wire:model.live.debounce="vendedor_id" :options="$arr_vendedores" optionLabel="nome" placeholder="Filtra por vendedor" 
            single />
        @endhasanyrole
    </div>

    <x-stat 
        title="Total Contratos" 
        value="{{ $totalContratos }}" 
        icon="o-document-text" 
        {{-- tooltip="Total de contratos cadastrados" --}}
        />
    
    <x-stat
        title="Contratos"
        description="Este mês"
        value="{{ $thisMonthContratos }}"
        icon="o-arrow-trending-up"
        {{-- tooltip-bottom="Contratos cadastrados no mês atual" --}}
        />
    
    <x-stat
        title="Pendentes"
        description="Aguardando processamento"
        value="{{ $pendingContratos }}"
        icon="o-clock"
        {{-- tooltip-left="Contratos com status pendente" --}}
        />
    
    <x-stat
        title="Produção"
        description="Valor total"
        value="R$ {{ $totalProducao }}"
        icon="o-currency-dollar"
        class="text-green-500"
        color="text-green-700"
        {{-- tooltip-right="Valor total da produção" --}}
        />

    @php
        $headers = [
            ['key' => 'name', 'label' => 'Etapas', 'sortable' => false],
            ['key' => 'count', 'label' => 'Contratos', 'sortable' => false],
        ];
    @endphp
    
    {{-- You can use any `$wire.METHOD` on `@row-click` --}}
    <x-card>
        <div class="max-h-96 overflow-y-auto select-none">
            <x-table :headers="$headers" :rows="$values_by_status" striped no-hover show-empty-text>
                @scope('cell_count', $data)
                    <x-badge :value="$data['count']" @class([
                        'badge-success' => $data['count'] > 0,
                        'badge-error' => $data['count'] == 0,
                    ]) />
                @endscope
            </x-table>
        </div>
    </x-card>
</div>