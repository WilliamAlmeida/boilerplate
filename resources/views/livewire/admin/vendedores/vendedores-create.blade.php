<div>
    <x-drawer wire:model="showDrawer" title="Novo Vendedor" separator right with-close-button class="lg:w-1/3">
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <x-input label="Nome" wire:model="form.nome" placeholder="Nome do vendedor" required />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" wire:click="$set('showDrawer', false)" />
            <x-button label="Salvar" wire:click="save" class="btn-primary" spinner />
        </x-slot:actions>
    </x-drawer>
</div>
