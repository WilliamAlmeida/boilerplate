<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer" title="Nova Permissão" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/3">
        <div class="space-y-2" x-data="{
            selecteds: $wire.entangle('selected'),
            permissions: $wire.entangle('permissions'),
            toggleSelectAll (action) {
                $el.querySelectorAll('[type=checkbox]').forEach((e) => {
                    if(window.getComputedStyle(e.closest('li')).display !== 'none') {
                        e.checked = action;
                        let index = this.selecteds.indexOf(parseInt(e.name));
                        if(action) { if(index === -1) this.selecteds.push(parseInt(e.name)) }else{ if(index !== -1) this.selecteds.splice(index, 1) }
                    }
                });
            },
            generateCrud () {
                if(!this.permissions.length) return;
                let crud = ['create', 'edit', 'view', 'viewAny', 'delete', 'forceDelete'];

                const permissionInput = this.permissions[0].split('.')[0];
                this.permissions = [];

                crud.forEach((c) => { 
                    this.permissions.push(`${permissionInput}.${c}`);
                });
            }
        }">
            <x-tags label="Permissões" wire:model="permissions" hint="Tecle enter para adicionar uma nova permissão" clearable />
            <div class="flex gap-x-3" x-show="permissions.length == 1">
                <x-button primary label="Gerar CRUD" x-on:click="generateCrud" class="flex-1" />
                <x-button primary label="Limpar" x-on:click="permissions = []" class="flex-1" />
            </div>

            @if(!empty($roles))
                <div class="select-none" x-data="{
                    toggleSelect (option) {
                        let index = selecteds.indexOf(option);
                        if(index !== -1) { selecteds.splice(index, 1) }else{ selecteds.push(option) }
                    }
                }">
                    <div class="flex justify-between">
                        <span class="font-bold text-sm">Funções</span>
                        <div>
                            <x-button class="btn-error btn-sm" icon="o-minus-circle" x-on:click="toggleSelectAll(0)" />
                            <x-button class="btn-success btn-sm" icon="o-check-circle" x-on:click="toggleSelectAll(1)" />
                        </div>
                    </div>

                    <ul class="space-y-2">
                        @foreach ($roles as $role)
                            <li>
                                <x-checkbox id="c{{ $role['id'] }}" label="{{ $role['name'] }}" name="{{ $role['id'] }}" x-on:click="toggleSelect({{ $role['id'] }})" />
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" x-on:click="$wire.showDrawer = false" />
            @can('permissions.create')
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="save" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
