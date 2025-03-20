<div>
    <!-- HEADER -->
    <x-header title="Permissões" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Buscar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filtros" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
            @if($can->forceDelete)
                <x-button label="Ações em massa" @click="$wire.drawer2 = true" responsive icon="phosphor.note-pencil-duotone" class="btn-primary" />
            @endif
            @if($can->create)
                <x-button label="Nova" wire:click="$dispatch('create')" responsive icon="o-plus" class="btn-primary" />
            @endif
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$permissions" :sort-by="$sortBy" with-pagination wire:model="selected" selectable per-page="perPage" :per-page-values="[20, 50, 100]">
            @scope('actions', $data, $can)
            <div class="flex items-center space-x-2">
                @if($can->view || $can->edit)
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
        <div class="flex flex-col space-y-4">
            <x-input placeholder="Buscar..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />
            <x-select 
                label="Filtrar por Função" 
                wire:model.live.debounce.250ms="role_id" 
                :options="$roles" 
                placeholder="Selecione uma função" 
                placeholder-value="" 
                clearable
            />
        </div>

        <x-slot:actions>
            <x-button label="Resetar" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Feito" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>

    <!-- BULK ACTIONS DRAWER -->
    <x-drawer wire:model="drawer2" title="Ações em massa" right separator with-close-button class="lg:w-1/3">
        <div class="space-y-3" x-data="{ selecteds: $wire.entangle('selected') }">
            <x-button label="Deletar Selecionados" icon="o-trash" class="btn-error" wire:click="bulk_delete" wire:confirm="Tem certeza que deseja deletar os registros selecionados?" spinner x-bind:disabled="!selecteds.length" />
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" icon="o-x-mark" x-on:click="$wire.drawer2 = false" />
        </x-slot:actions>
    </x-drawer>

    <livewire:admin.permissions.permission-create />
    <livewire:admin.permissions.permission-edit />
</div>