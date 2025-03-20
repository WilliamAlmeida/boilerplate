<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="{{ $name }}" subtitle="Permissão #{{ $permission?->id }}" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/3">
        <div class="space-y-2" x-data="{
            permission: @entangle('name'),
            selecteds: @entangle('selected'),
            toggleSelectAll (action) {
                $el.querySelectorAll('[type=checkbox]').forEach((e) => {
                    if(window.getComputedStyle(e.closest('li')).display !== 'none') {
                        e.checked = action;
                        let index = this.selecteds.indexOf(parseInt(e.name));
                        if(action) { if(index === -1) this.selecteds.push(parseInt(e.name)) }else{ if(index !== -1) this.selecteds.splice(index, 1) }
                    }
                });
            }
        }">
            <x-input label="Permissão" wire:model="name" clearable />
            
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
                                <x-checkbox id="e{{ $role['id'] }}" label="{{ $role['name'] }}" name="{{ $role['id'] }}" x-on:click="toggleSelect({{ $role['id'] }})" :checked="in_array($role['id'], $selected)" />
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" x-on:click="$wire.showDrawer2 = false" />
            @can('permissions.edit')
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="update" />
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
