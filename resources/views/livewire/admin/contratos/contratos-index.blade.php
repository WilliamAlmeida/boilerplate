<div>
    <!-- HEADER -->
    <x-header title="Contratos" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filtros" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
            @if($can->create)
                <x-button label="Novo" wire:click="$dispatch('create')" responsive icon="o-plus" class="btn-primary" />
                <x-button label="Importar" wire:click="$dispatch('import')" responsive icon="o-document-arrow-up" class="btn-primary" />
            @endif
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$contratos" :sort-by="$sortBy" with-pagination per-page="perPage" :per-page-values="[20, 50, 100]">
            @scope('cell_deleted_at', $data)
                <x-btn-toggle-deleted_at :data="$data" />
            @endscope
            @scope('actions', $data, $can)
            <div class="flex items-center space-x-2">
                @if($can->edit || $can->view)
                    <x-button icon="o-pencil" wire:click="$dispatch('edit', { id: {{ $data->id }} })" spinner class="btn-ghost btn-sm text-blue-500" />
                @endif
                @if($can->forceDelete)
                    <x-btn-delete :data="$data" />
                @endif
            </div>
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filtros" right separator with-close-button class="lg:w-1/3">
        <div class="flex flex-col space-y-2">
            <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

            <x-choices label="Cliente" wire:model="filter.cliente" :options="$clientesSearchable" search-function="searchClients" optionLabel="nome_fantasia" placeholder="Selecione uma opção" single searchable />

            <div class="grid grid-cols-2 gap-4">
                <x-input label="Vendedor" wire:model="filter.vendedor" clearable />
                <x-input label="CPF" wire:model="filter.cpf" x-mask="999.999.999-99" clearable />
            </div>

            <div class="flex flex-col">
                <x-mary.label label="Data da Inclusão" class="font-semibold" />
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Início" wire:model="filter.data_i" type="date" />
                    <x-input label="Término" wire:model="filter.data_e" type="date" />
                </div>
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Resetar" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Feito" icon="o-check" class="btn-primary"  @click="$dispatch('table:refresh'); $wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>

    <livewire:admin.contratos.contratos-create />
    <livewire:admin.contratos.contratos-edit />
    <livewire:admin.contratos.contratos-import />
</div>
