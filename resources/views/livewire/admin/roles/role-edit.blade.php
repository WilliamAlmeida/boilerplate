<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer2" title="{{ $name }}" subtitle="Função #{{ $role?->id }}" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/2" x-data="{ selectedTab: $wire.entangle('selectedTab') }">
        <x-tabs wire:model="selectedTab">
            <x-tab name="permissions-tab" label="Permissões" icon="o-globe-alt">
                <div class="space-y-2" x-data="{
                    filtro: '',
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
                    <x-input label="Função" placeholder="Digite o nome da Função" wire:model="name" id="role_edit" />
                    
                    <div class="flex justify-between gap-x-3 mt-4">
                        <x-button class="btn-error" icon="o-minus-circle" x-on:click="toggleSelectAll(0)" />
                        <div class="flex-grow">
                            <x-input placeholder="Filtro" x-model="filtro" />
                        </div>
                        <template x-if="filtro.length">
                            <x-button class="btn-error btn-outline" icon="o-x-mark" x-on:click="filtro = ''" />
                        </template>
                        <x-button class="btn-success" icon="o-check-circle" x-on:click="toggleSelectAll(1)" />
                    </div>
        
                    <div>
                        <div class="mb-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-lg"><x-icon name="o-globe-alt" class="w-5 h-5 inline-block" /> Permissões</h3>
                            </div>
                        </div>
        
                        <div id="permissions-content">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @if(!empty($permissions))
                                    @foreach ($permissions as $group => $values)
                                        <div class="select-none" x-data="{
                                            toggleSelectAll (action) {
                                                $el.querySelectorAll('[type=checkbox]').forEach((e) => {
                                                    if(window.getComputedStyle(e.closest('li')).display !== 'none') {
                                                        e.checked = action;
                                                        let index = selecteds.indexOf(parseInt(e.name));
                                                        if(action) { if(index === -1) selecteds.push(parseInt(e.name)) }else{ if(index !== -1) selecteds.splice(index, 1) }
                                                    }
                                                });
                                            },
                                            toggleSelect (option) {
                                                let index = selecteds.indexOf(option);
                                                if(index !== -1) { selecteds.splice(index, 1) }else{ selecteds.push(option) }
                                            }
                                        }">
                                            <div class="flex justify-between">
                                                <span class="font-bold capitalize">{{ $group }}</span>
                                                <div>
                                                    <x-button class="btn-error btn-sm" icon="o-minus-circle" x-on:click="toggleSelectAll(0)" />
                                                    <x-button class="btn-success btn-sm" icon="o-check-circle" x-on:click="toggleSelectAll(1)" />
                                                </div>
                                            </div>
        
                                            <ul class="[&>*]:border-b [&>*:last-child]:border-b-0">
                                                @foreach ($values as $key => $permission)
                                                    <li x-show="filtro === '' || '{{ $permission['name'] }}'.toLowerCase().includes(filtro.toLowerCase())">
                                                        <x-checkbox id="{{ $permission['id'] }}" label="{{ $permission['name'] }}" name="{{ $permission['id'] }}" 
                                                            x-on:click="toggleSelect({{ $permission['id'] }})" :checked="in_array($permission['id'], $selected)" />
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-tab>
            <x-tab name="users-tab" icon="o-users">
                <x-slot:label>
                    Usuários
                    @if($role && $role->users->count())
                        <x-badge value="{{ $role->users->count() }}" class="badge-primary" />
                    @endif
                </x-slot:label>

                @if($role)
                    @forelse($role->users as $key => $user)
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center gap-x-2">
                                <div class="flex items-center gap-x-2">
                                    <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full">
                                        <x-icon name="o-user" class="w-5 h-5 text-gray-500" />
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-x-2">
                                @can('usuarios.edit_permissions')
                                    <x-button class="btn-error btn-sm" icon="o-trash" wire:click="removeUser({{ $user->id }})" wire:confirm="Tem certeza que deseja remover o usuário?" />
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="flex justify-center items-center h-10">
                            <span class="text-gray-500">Nenhum usuário encontrado</span>
                        </div>
                    @endforelse
                @endif
            </x-tab>
        </x-tabs>

        <x-slot:actions>
            <x-button label="Cancelar" x-on:click="$wire.showDrawer2 = false" />
            @can('roles.edit')
                <span x-show="selectedTab == 'permissions-tab'"><x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="update" /></span>
            @endcan
        </x-slot:actions>
    </x-drawer>
</div>
