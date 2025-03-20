<div>
    <div class="h-screen w-full flex flex-col gap-y-3 justify-center items-center bg-primary">
        <img src="{{ asset('img/logo.png') }}" class="mx-auto w-40 mb-5">

        <x-card class="mx-auto py-10 w-96 shadow-md">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Esqueceu sua senha? Sem problemas. Apenas nos informe seu endereço de e-mail e nós lhe enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form class="space-y-4" wire:submit="sendResetLink">
                <!-- Email Address -->
                <div>
                    <x-input label="E-mail" type="email" wire:model="email" autofocus />
                </div>
        
                <div class="flex items-center justify-center mt-4">
                    <x-button label="Receber Link de Redefinição de Senha" type="submit" class="btn-primary" />
                </div>
            </form>
        </x-card>

        <x-button label="Voltar" link="{{ route('login') }}" class="btn-ghost text-white btn-sm mt-3" />
    </div>
</div>