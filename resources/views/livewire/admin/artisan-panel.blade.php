<div>
    <x-modal wire:model="myModalArtisan" name="artisanPanelModal" title="Artisan Panel" class="backdrop-blur">
        <form id="artisanForm" wire:submit="runCommand" class="flex flex-col gap-y-3 min-h-[60vh]">
            <div class="flex flex-col gap-y-2">
                <x-input label="Comando" wire:model="command" placeholder="Digite o comando" hint="list; migrate:status;" />
                <x-choices-offline label="Atalhos" wire:model="command" :options="$suggestions" placeholder="Selecione um Comando" class="select-sm options-sm" single searchable />

                {{--
                    <label class="block text-sm" for="artisan_parameters">Parameters</label>
                    <x-wire-input id="artisan_parameters" placeholder="Separe each parameter with ;" wire:model="parameters" />
                --}}
            </div>

            @if($output)
                <div class="bg-gray-100 dark:bg-black dark:text-lime-400 mt-3 p-2 border border-secondary-300 dark:border-gray-600 rounded-l-md rounded-r-sm text-xs sm:text-sm text-nowrap overflow-auto max-h-96 soft-scrollbar">
                    {!! $output !!}
                </div>
            @endif
        </form>

        <x-slot:actions>
            <x-button label="Cancelar" x-on:click="$wire.myModalArtisan = false" wire:loading.attr="disabled" />
            <x-button label="Executar" form="artisanForm" class="btn-primary" type="submit" spinner="runCommand" loading-delay="long" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-modal>
</div>