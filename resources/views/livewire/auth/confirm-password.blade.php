<div>
    <div class="h-screen w-full flex flex-col gap-y-3 justify-center items-center bg-primary">
        <img src="{{ asset('img/logo.png') }}" class="mx-auto w-40 mb-5">

        <x-card class="mx-auto py-10 w-96 shadow-md">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar.
            </div>

            <form class="space-y-4" wire:submit="confirmPassword">
                <!-- Password -->
                <div>
                    <x-password label="Senha" wire:model="password" autofocus />
                </div>

                <div class="flex items-center justify-center mt-4">
                    <x-button label="Confirmar" type="submit" class="btn-primary" />
                </div>
            </form>
        </x-card>

        <x-button label="Voltar" :link="route('login')" class="btn-ghost text-white btn-sm mt-3" />
    </div>
</div>