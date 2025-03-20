<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="Novo Usuário" subtitle="" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/3">
        <div class="space-y-2">
            <x-input label="Nome" wire:model="form.name" clearable />
            <x-input label="E-mail" wire:model="form.email" clearable />
            <x-password label="Senha" wire:model="form.password" type="password" clearable />
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.showDrawer2 = false" />
            @can('usuarios.create')
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="save()" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>