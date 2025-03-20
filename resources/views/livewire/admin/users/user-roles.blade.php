<div>
    <!-- MODAL -->
    <x-modal wire:model="showModal" title="{{ $user?->name }}" subtitle="Registro #{{ $user?->id }}" separator box-class="min-h-[50vh]">
        @if($user)
            <div class="space-y-2">
                @if($editmode)
                    <x-choices label="Funções" wire:model="selectedRoles" :options="$roles" searchable hint="Selecione as funções para este usuário" />
                @else
                    <div class="mb-4">
                        <x-mary.label label="Funções do Usuário" />
                        <div class="mt-1 flex flex-wrap gap-2">
                            @forelse($user->roles as $key => $role)
                                <x-badge value="{{ $role->name }}" class="badge-primary" />
                            @empty
                                <span class="text-gray-500 text-sm">Nenhuma função atribuída</span>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Fechar" x-on:click="$wire.showModal = false" />
            @if($editmode && auth()->user()->can('usuarios.edit_permissions'))
                <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="update" />
            @endif
        </x-slot:actions>
    </x-modal>
</div>