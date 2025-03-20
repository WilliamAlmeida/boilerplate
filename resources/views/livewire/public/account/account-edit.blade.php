<div>
    <!-- HEADER -->
    <x-header title="Edição da Conta" separator progress-indicator />

    <!-- TABLE  -->
    <x-card>
        <x-tabs wire:model="selectedTab">
            <x-tab name="account-tab" label="Informação" icon="o-users">
                <div class="flex flex-col gap-4">
                    <h2 class="font-bold text-lg">Informação da Conta</h2>
                    <p>Atualize as informações da sua conta e o endereço de e-mail.</p>
                
                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                        <div>
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                Seu endereço de e-mail não está verificado.
                                <x-button label="Clique aqui para reenviar o e-mail de verificação." class="btn-warning btn-sm" :link="route('verification.send')" />
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    Um novo link de verificação foi enviado para o seu endereço de e-mail.
                                </p>
                            @endif
                        </div>
                    @endif
                    
                    {{-- <x-forms.input-foto :form="$this" wireModel="foto_tmp" /> --}}
                
                    <form class="flex flex-col gap-4" wire:submit="atualizar_conta">
                        <x-input label="Nome" placeholder="Nome" wire:model="name" />
                        <x-input label="E-mail" placeholder="E-mail" wire:model="email" type="email" />
                        <x-choices label="Fuso Horário" wire:model="timezone" :options="$this->timezones" placeholder="Selecione uma opção" single clearable  />
                
                        <div class="flex justify-end"><x-button label="Salvar" class="btn-primary" type="submit" /></div>
                    </form>
                </div>
            </x-tab>
            <x-tab name="password-tab" label="Senha" icon="o-lock-closed">
                <div class="flex flex-col gap-4">
                    <h2 class="font-bold text-lg">Atualizar a senha</h2>
                    <p>Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.</p>
                
                    <div x-data="{ show: @entangle('passwordForm') }">
                        <form class="flex flex-col gap-4" wire:submit="atualizar_senha" x-show="show" x-transition>
                            <x-password label="Senha Atual" wire:model="current_password" clearable />
                            <x-password label="Nova Senha" wire:model="password" clearable />
                            <x-password label="Confirmação da Senha" wire:model="password_confirmation" clearable />
                
                            <div class="flex justify-between">
                                <x-button negative label="Cancelar" x-on:click="show = false; $wire.cancel_validation()" />
                                <x-button label="Salvar" class="btn-primary" type="submit" />
                            </div>
                        </form>
                
                        <x-button label="Trocar Senha" class="btn-primary" x-on:click="show = true" x-show="!show" />
                    </div>
                </div>
            </x-tab>
            <x-tab name="delete-tab" label="Deletar Conta" icon="o-shield-exclamation">
                <div class="flex flex-col gap-4">
                    <h2 class="font-bold text-lg">Deletar Conta</h2>
                    <p>Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir sua conta, baixe todos os dados ou informações que deseja reter.</p>
                
                    <x-button label="Deletar Conta" class="btn-error" @click="$wire.deleteAccountModal = true" />
                </div>

                <x-modal wire:model="deleteAccountModal"
                    title="Deseja deletar sua conta?"
                    subtitle="Uma vez que sua conta é deletada, todos os dados serão permanentemente deletados. Por favor, entre com a sua senha para confirmar que você quer deletar permanentemente sua conta."
                    separator
                    >
                    <div>
                        <x-password label="Senha" wire:model="password_to_delete" id="current_password_2" class="input-error" clearable />
                    </div>
                 
                    <x-slot:actions>
                        <x-button label="Cancelar" @click="$wire.deleteAccountModal = false" />
                        <x-button label="Deletar Conta" class="btn-error" wire:click="deletar_conta" />
                    </x-slot:actions>
                </x-modal>
            </x-tab>
        </x-tabs>
    </x-card>
</div>
