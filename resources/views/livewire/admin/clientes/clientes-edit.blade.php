<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="Editar Cliente" subtitle="Registro #{{ $cliente?->id }}" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/3">
        <div class="space-y-2">
            <x-input label="Nome" wire:model="form.nome" clearable required />
            <x-input label="E-mail" wire:model="form.email" clearable required />
            <x-datetime label="Data de Nascimento" wire:model="form.data_nascimento" icon="o-calendar" without-time />
            <x-tags label="Tags" wire:model="form.tags_personalidade" hint="Selecione múltiplas tags que representam a personalidade do cliente" />
        </div>

        <x-errors title="Oops!" description="Por favor, corrija os erros abaixo." icon="o-face-frown" />

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.showDrawer2 = false" />
            @can('clientes.edit')
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="update()" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
