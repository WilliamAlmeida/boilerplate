<div>
    <!-- HEADER -->
    <x-header title="Vendedores" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filtros" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
            @if($can->create)
                <x-button label="Novo" wire:click="$dispatch('create')" responsive icon="o-plus" class="btn-primary" />
            @endif
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$vendedores" :sort-by="$sortBy" with-pagination per-page="perPage" :per-page-values="[20, 50, 100]">
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
        </div>

        <x-slot:actions>
            <x-button label="Resetar" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Feito" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>

    <livewire:admin.vendedores.vendedores-create />
    <livewire:admin.vendedores.vendedores-edit />
</div>
