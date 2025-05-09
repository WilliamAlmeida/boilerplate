<div>
    <!-- HEADER -->
    <x-header title="Usuários" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filtros" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
            <x-button label="Novo" wire:click="$dispatch('create')" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('cell_roles', $data)
                {{-- <x-badge :value="$data->getTypeUser()" class="badge-primary" /> --}}
                {{ Str::title($data->roles->pluck('name')->join(', ')) }}
            @endscope
            @scope('cell_email', $data, $can)
                @if($can->edit)
                    @if(!$data->hasVerifiedEmail())
                        <x-button icon="o-envelope" wire:click="toggleVerify('{{ $data->id }}')" spinner class="btn-ghost btn-sm text-red-500" />
                    @else
                        <x-button icon="o-envelope-open" wire:click="toggleVerify('{{ $data->id }}')" spinner class="btn-ghost btn-sm text-green-500" />
                    @endif
                @endif
                <span class="{{ $data->email_verified_at ? 'text-primary' : null }}">{{ $data->email }}</span>
            @endscope
            @scope('cell_deleted_at', $data)
            @if(auth()->id() != $data->id)
                <x-btn-toggle-deleted_at :data="$data" />
            @endif
            @endscope
            @scope('actions', $data, $can)
            <div class="flex items-center space-x-2">
                <x-button icon="o-pencil" wire:click="$dispatch('edit', { id: {{ $data->id }} })" spinner class="btn-ghost btn-sm text-blue-500" />
                @if($can->edit_permissions)
                    <x-button icon="o-key" wire:click="$dispatch('edit-roles', { id: {{ $data->id }} })" spinner class="btn-ghost btn-sm text-blue-500" />
                @endif
                @if($can->forceDelete && auth()->id() != $data->id)
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

    <livewire:admin.users.user-create />
    <livewire:admin.users.user-edit />
    <livewire:admin.users.user-roles />
</div>