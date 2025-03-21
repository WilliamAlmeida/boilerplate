<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="Editar Contrato" subtitle="Atualizar informações do contrato" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/2">
        <x-tabs wire:model="selectedTab">
            <x-tab name="info-tab" label="Informações Básicas" icon="o-document-text">
                <div class="space-y-3">
                    <!-- Cliente Section -->
                    <x-choices label="Cliente" wire:model.live.debounce="form.cliente_id" :options="$clientesSearchable" search-function="searchClients" optionLabel="nome_fantasia" placeholder="Selecione uma opção" single searchable />

                    <x-input label="Documento do cliente" wire:model="form.cpf" x-mask="999.999.999-99" />

                    <!-- Dados Financeiros -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <x-input label="Valor PMT (R$)" wire:model="form.pmt" icon="o-currency-dollar" money locale="pt-BR" clearable />
                        <x-input label="Prazo" wire:model="form.prazo" placeholder="Em meses" type="number" />
                        <x-input label="Taxa Original (%)" wire:model="form.taxa_original" placeholder="0.00" type="number" step="0.01" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <x-input label="Saldo Devedor (R$)" wire:model="form.saldo_devedor" icon="o-currency-dollar" money locale="pt-BR" clearable />
                        <x-input label="Produção (R$)" wire:model="form.producao" icon="o-currency-dollar" money locale="pt-BR" clearable />
                        <x-input label="Troco Cliente (R$)" wire:model="form.troco_cli" icon="o-currency-dollar" money locale="pt-BR" clearable />
                    </div>
                </div>
            </x-tab>
            <x-tab name="details-tab" label="Detalhes Adicionais" icon="o-clipboard-document-list">
                <div class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-input label="Pós Venda" wire:model="form.pos_venda" placeholder="Status da pós venda" />
                        <x-input label="Vendedor" wire:model="form.vendedor" placeholder="Nome do vendedor" />
                    </div>

                    <x-input label="Data de Inclusão" wire:model="form.data_inclusao" type="date" />
                </div>
            </x-tab>
        </x-tabs>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.showDrawer2 = false" />
            @can('contratos.edit')
                <x-button label="Atualizar" class="btn-primary" icon="o-check" wire:click="update()" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
