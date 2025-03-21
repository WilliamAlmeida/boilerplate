<div>
    <x-drawer wire:model="showDrawer2" title="Editar Vendedor" subtitle="Registro #{{ $vendedor?->id }}" separator right with-close-button class="lg:w-1/3">
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <x-input label="Nome" wire:model="form.nome" placeholder="Nome do vendedor" required />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" wire:click="$set('showDrawer2', false)" />
            <x-button label="Salvar" wire:click="update" class="btn-primary" spinner />
        </x-slot:actions>
    </x-drawer>
</div>
