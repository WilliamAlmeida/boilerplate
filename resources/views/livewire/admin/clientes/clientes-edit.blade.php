<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="Editar Cliente" subtitle="Registro #{{ $cliente?->id }}" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/2">
        <div x-data="{
            tipo: $wire.entangle('form.tipo'),
        }">
            <x-tabs wire:model="selectedTab">
                <x-tab name="info-tab" label="Dados" icon="o-list-bullet">
                    <div class="space-y-2">
                        <x-select label="Tipo" wire:model="form.tipo" :options="$tipos" placeholder="Selecione uma opção" placeholder-value="" />
                        <x-input label="Nome Fantasia" wire:model="form.nome_fantasia" clearable />
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                            <div x-show="tipo && tipo != 'Físico'" class="col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3">
                                <x-input label="CNPJ" wire:model="form.cnpj" maxlength="18" x-mask="99.999.999/9999-99" clearable />
                                <x-input label="Razão Social" wire:model="form.razao" />
                            </div>
                            <div x-show="tipo && tipo == 'Físico'" class="col-span-2">
                                <x-input label="CPF" wire:model="form.cpf" maxlength="14" x-mask="999.999.999-99" clearable />
                            </div>
                        </div>
                    </div>
                </x-tab>
                <x-tab name="address-tab" label="Endereço" icon="o-map">
                    <div class="space-y-2">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-input label="CEP" placeholder="00.000-000" wire:model="form.cep" x-mask="99.999-999">
                                <x-slot:append>
                                    <x-button icon="o-magnifying-glass" class="btn-primary rounded-s-none" wire:click="pesquisar_cep" />
                                </x-slot:append>
                            </x-input>
            
                            <x-select label="Estado" wire:model.blur="form.estado_id" :options="$array_estados" option-label="uf" placeholder="Selecione o Estado" placeholder-value="" />
                            <x-select label="Município" wire:model="form.cidade_id" :options="$array_cidades" option-label="nome" placeholder="Selecione o Município" placeholder-value="" wire:loading.attr="disabled" wire:target="form.estado_id" />
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <x-input label="Bairro" placeholder="Informe o Bairro" wire:model="form.bairro" />
                            <x-input label="Logradouro" placeholder="Informe o Logradouro" wire:model="form.endereco" />
                        </div>
                    </div>
                </x-tab>
                <x-tab name="contact-tab" label="Contatos" icon="o-phone">
                    <div class="space-y-2">
                        @include('livewire.admin.clientes.includes.emails')
                        @include('livewire.admin.clientes.includes.numeros')
                    </div>
                </x-tab>
            </x-tabs>
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.showDrawer2 = false" />
            @can('clientes.edit')
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="update()" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
