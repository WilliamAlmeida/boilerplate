<div>
    <div class="h-screen w-full flex flex-col gap-y-3 justify-center items-center bg-primary">
        <img src="{{ asset('img/logo.png') }}" class="mx-auto w-40 mb-5">

        <x-card class="mx-auto py-10 w-96 shadow-md">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, enviaremos outro com prazer.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <x-button label="Reenviar E-mail de Verificação" class="btn-primary" wire:click="resendVerificationEmail" />

                <x-button label="Sair" class="btn-ghost btn-sm" :link="route('logout')" />
            </div>
        </x-card>
    </div>
</div>