<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer" title="Novo Contrato" subtitle="Cadastrar novo contrato" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/2">
        <x-tabs wire:model="selectedTab">
            <!-- Tab Cliente e Dados Básicos -->
            <x-tab name="cliente-tab" label="Cliente" icon="o-user">
                <div class="space-y-4">
                    <x-choices label="Cliente" wire:model.live.debounce="form.cliente_id" :options="$clientesSearchable" search-function="searchClients" optionLabel="nome_fantasia" 
                        placeholder="Selecione uma opção" single searchable />

                    <x-input label="Documento do cliente" wire:model="form.cpf" x-mask="999.999.999-99" />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-input label="Telefone" wire:model="form.telefone" placeholder="Telefone de contato" x-mask="(99) 99999-9999" maxlength="20" />
                        <x-input label="Data de Inclusão" wire:model="form.data_inclusao" type="date" />
                    </div>
                </div>
            </x-tab>

            <!-- Tab Dados Financeiros -->
            <x-tab name="financeiro-tab" label="Dados Financeiros" icon="o-banknotes">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <x-input label="Valor PMT (R$)" wire:model="form.pmt" icon="o-currency-dollar" money locale="pt-BR" clearable />
                        <x-input label="Prazo (meses)" wire:model="form.prazo" placeholder="Em meses" type="number" />
                        <x-input label="Taxa Original (%)" wire:model="form.taxa_original" placeholder="0.00" type="number" step="0.01" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-input label="Valor Financiado (R$)" wire:model="form.financiado" icon="o-currency-dollar" money locale="pt-BR" clearable />
                        <x-input label="Saldo Devedor (R$)" wire:model="form.saldo_devedor" icon="o-currency-dollar" money locale="pt-BR" clearable />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-input label="Produção (R$)" wire:model="form.producao" icon="o-currency-dollar" money locale="pt-BR" clearable />
                        <x-input label="Troco Cliente (R$)" wire:model="form.troco_cli" icon="o-currency-dollar" money locale="pt-BR" clearable />
                    </div>
                </div>
            </x-tab>

            <!-- Tab Produto e Banco -->
            <x-tab name="produto-tab" label="Produto/Banco" icon="o-building-library">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <x-input label="Banco/Perfil" wire:model="form.banco_perfil" placeholder="Banco ou perfil" maxlength="50" />
                        <x-input label="Produto" wire:model="form.produto" placeholder="Nome do produto" maxlength="50" />
                        <x-input label="Tabela" wire:model="form.tabela" placeholder="Tabela aplicada" type="number" step="0.01" min="0" />
                    </div>
                </div>
            </x-tab>

            <!-- Tab Status e Fluxo -->
            <x-tab name="status-tab" label="Status" icon="o-clipboard-document-check">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-choices-offline label="Status do Contrato" wire:model="form.status" :options="$arr_status" placeholder="Selecione um status" 
                            single searchable />
                        <x-input label="Pós Venda" wire:model="form.pos_venda" placeholder="Status da pós venda" />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-choices-offline label="Vendedor" wire:model="form.vendedor_id" :options="$arr_vendedores" optionLabel="nome" placeholder="Selecione um vendedor" 
                            single searchable />
                    </div>
                </div>
            </x-tab>

            <!-- Tab Observações -->
            <x-tab name="obs-tab" label="Observações" icon="o-chat-bubble-bottom-center-text">
                <div class="space-y-4">
                    <x-textarea label="Observações (1)" wire:model="form.obs_1" placeholder="Detalhes adicionais sobre o financiamento" rows="3" />
                    <x-textarea label="Observações (2)" wire:model="form.obs_2" placeholder="Anotações complementares" rows="3" />
                </div>
            </x-tab>

            <x-errors title="Oops!" description="Please, fix them." icon="o-face-frown" />
        </x-tabs>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.showDrawer = false" />
            @can('contratos.create')
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="save()" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
