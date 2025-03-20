<div>
    <div class="h-screen w-full flex flex-col gap-y-3 justify-center items-center bg-primary">
        <img src="{{ asset('img/logo.png') }}" class="mx-auto w-40 mb-5">

        <x-card class="mx-auto py-10 w-96 shadow-md" x-data="{
            email: $wire.entangle('email'),
            password: $wire.entangle('password'),
            password_confirmation: $wire.entangle('password_confirmation'),
            timezone: $wire.entangle('timezone'),
            init() {
                this.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            }
        }">
            <form class="space-y-4" wire:submit="register">
                <!-- Name -->
                <x-input label="Nome" type="text" wire:model="name" autofocus autocomplete="name" inline icon="o-user" />
                
                <!-- Email Address -->
                <x-input label="E-mail" type="email" wire:model="email" autocomplete="username" inline icon="o-envelope" />
                <div class="font-bold text-xs text-primary mt-2" x-show="email" x-cloak>Coloque um e-mail válido, pois será necessário validação para usar o sistema.</div>
                
                <!-- Password -->
                <x-password label="Senha" wire:model="password" autocomplete="new-password" inline password-icon="o-lock-closed" password-visible-icon="o-lock-open" />
                
                <!-- Confirm Password -->
                <x-password label="Confirmação de senha" wire:model="password_confirmation" autocomplete="new-password" inline password-icon="o-lock-closed" password-visible-icon="o-lock-open" />

                <template x-if="password && password == password_confirmation">
                    <x-select label="Fuso horário" icon="o-globe-alt" :options="$this->timezones" wire:model="timezone" inline />
                    {{-- <x-choices label="Fuso horário" wire:model="timezone" :options="$this->timezones" placeholder="Selecione uma opção" inline single clearable  /> --}}
                </template>

                <!-- Accept Terms -->
                <div class="flex gap-x-3">
                    <x-checkbox id="terms" wire:model="terms" />
                    <span class="text-sm">Eu concordo com os <a href="#" class="underline hover:no-underline">Termos de Serviço</a> e a <a href="#" class="underline hover:no-underline">Política de Privacidade</a></span>
                </div>

                <div class="flex items-center justify-end">
                    <x-button label="Criar Conta" type="submit" class="btn-primary" icon="o-paper-airplane" />
                </div>
            </form>
        </x-card>

        <div class="w-96 flex justify-between items-center mt-3">
            <x-button label="Início" icon="o-chevron-left" link="{{ route('home') }}" class="btn-ghost text-white btn-sm" />
            <x-button label="Já tem conta? Entrar" link="{{ route('login') }}" class="btn-ghost text-white btn-sm" />
        </div>
    </div>
</div>
