<div>
    <!-- DRAWER -->
    <x-drawer wire:model="showDrawer" title="{{ $form->name }}" subtitle="Registro #{{ $user?->id }}" separator with-close-button close-on-escape right
        class="w-11/12 lg:w-1/3">
        <div class="space-y-2">
            <x-select label="Tipo de Usuário" wire:model="form.type" :options="App\Models\User::$list_type_user" option-label="label" option-value="type" :disabled="!$editmode" />
            <x-input label="Nome" wire:model="form.name" :readonly="!$editmode" :clearable="$editmode" />
            <x-input label="E-mail" wire:model="form.email" :readonly="!$editmode" :clearable="$editmode" />
            <x-input label="Login" wire:model="form.name" :readonly="!$editmode" :clearable="$editmode" />
            <div x-data="{
                newPassword: $wire.entangle('newPassword')
            }">
                <div x-show="newPassword">
                    <x-password label="Nova Senha" wire:model="form.password" class="input-error">
                        <x-slot:append>
                            {{-- Add `rounded-s-none` class (RTL support) --}}
                            <x-button label="Cancelar" icon="o-x-mark" class="btn-error rounded-s-none" x-on:click="newPassword = false" />
                        </x-slot:append>
                    </x-password>
                </div>
                <label class="pt-0 label label-text font-semibold col-span-2 text-primary" x-show="!newPassword">Clique no botão abaixo para alterar a senha do usuário.</label>
                <x-button label="Alterar senha" icon="o-key" class="btn-primary btn-sm" x-on:click="newPassword = true" x-show="!newPassword" />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancelar" x-on:click="$wire.showDrawer = false" />
            @if($editmode)
                @can('usuarios.edit')
                    <x-button label="Salvar" class="btn-primary" icon="o-check" wire:click="update" />
                @endcan
            @endif
        </x-slot:actions>
    </x-drawer>
</div>