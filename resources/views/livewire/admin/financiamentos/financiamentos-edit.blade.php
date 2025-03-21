<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="Editar Financiamento" subtitle="Atualizar informações do financiamento" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/2">
        <x-tabs wire:model="selectedTab">
            <x-tab name="info-tab" label="Informações Básicas" icon="o-document-text">
            <div class="space-y-3">
                <!-- Cliente Section -->
                <x-choices label="Cliente" wire:model.live.debounce="form.cliente_id" :options="$clientesSearchable" search-function="searchClients" optionLabel="nome_fantasia" placeholder="Selecione uma opção" single searchable />

                <x-input label="Documento do cliente" wire:model="form.cpf" x-mask="999.999.999-99" maxlength="14" />

                <!-- Dados do Financiamento -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <x-input label="Banco/Perfil" wire:model="form.banco_perfil" placeholder="Banco ou perfil" maxlength="50" />
                <x-input label="Produto" wire:model="form.produto" placeholder="Nome do produto" maxlength="50" />
                <x-input label="Tabela" wire:model="form.tabela" placeholder="Tabela aplicada" type="number" step="0.01" min="0" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <x-input label="Valor PMT (R$)" wire:model="form.pmt" icon="o-currency-dollar" money locale="pt-BR" clearable />
                <x-input label="Valor Financiado (R$)" wire:model="form.financiado" icon="o-currency-dollar" money locale="pt-BR" clearable />
                <x-input label="Produção (R$)" wire:model="form.producao" icon="o-currency-dollar" money locale="pt-BR" clearable />
                </div>
            </div>
            </x-tab>
            <x-tab name="details-tab" label="Detalhes Adicionais" icon="o-clipboard-document-list">
            <div class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <x-input label="Telefone" wire:model="form.telefone" placeholder="Telefone de contato" x-mask="(99) 99999-9999" maxlength="20" />
                <x-input label="Vendedor" wire:model="form.vendedor" placeholder="Nome do vendedor" maxlength="100" />
                </div>

                <x-select 
                label="Status" 
                wire:model="form.status" 
                placeholder="Selecione um status" 
                :options="[
                    ['name' => 'Aprovado', 'id' => 'Aprovado'],
                    ['name' => 'Negado', 'id' => 'Negado'],
                    ['name' => 'Em análise', 'id' => 'Em análise'],
                    ['name' => 'Pendente', 'id' => 'Pendente']
                ]" 
                option-label="name" 
                option-value="id" 
                />

                <x-input label="Data" wire:model="form.data" type="date" />

                <x-textarea label="Observações" wire:model="form.obs" placeholder="Detalhes adicionais sobre o financiamento" />
            </div>
            </x-tab>
        </x-tabs>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.showDrawer2 = false" />
            @can('financiamentos.edit')
                <x-button label="Atualizar" class="btn-primary" icon="o-check" wire:click="update()" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
