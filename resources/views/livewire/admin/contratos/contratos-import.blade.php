<div>
    <x-modal wire:model="myModal" title="Importação de Contratos" separator>
        @if(!$importing && !$importComplete)
            <x-file wire:model="file" label="Arquivo" hint="Only xlsx files are allowed" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" spinner />
        @endif

        @if($importing)
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Importando dados...</h3>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $totalRows ? ($currentRow / $totalRows) * 100 : 0 }}%"></div>
                    </div>
                    <div class="text-sm text-gray-600">
                        Processando linha {{ $currentRow }} de {{ $totalRows }}
                    </div>
                </div>
            </div>
        @endif

        @if($importComplete)
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Importação Concluída</h3>
                <p class="text-gray-600">Total de registros importados: {{ $importedCount }}</p>
                
                @if(count($errors) > 0)
                    <div class="mt-4">
                        <h4 class="font-medium text-red-600">Erros ({{ count($errors) }})</h4>
                        <div class="mt-2 max-h-64 overflow-y-auto bg-gray-50 p-3 rounded text-sm">
                            <ul class="list-disc pl-5">
                                @foreach($errors as $error)
                                    <li class="text-red-600">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <x-slot:actions>
            @if(!$importing)
                <x-button label="Fechar" @click="$wire.myModal = false" spinner />
                @if(!$importComplete)
                    <x-button label="Importar" class="btn-primary" wire:click="import" wire:loading.attr="disabled" spinner />
                @endif
            @endif
        </x-slot:actions>
    </x-modal>
</div>