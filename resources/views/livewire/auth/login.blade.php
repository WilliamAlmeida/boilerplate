<div>
    <div class="h-screen w-full flex flex-col gap-y-3 justify-center items-center bg-primary">
        <img src="{{ asset('img/logo.png') }}" class="mx-auto w-40 mb-5">

        <x-card class="mx-auto py-10 w-96 shadow-md">
            @if($display_demo)
                <div class="flex flex-col gap-y-2 mb-3 -mt-5">
                    <span class="font-semibold text-center">Acesso de demonstração</span>
                    <span class="text-xs">Utilize as credenciais abaixo para acessar a versão de demonstração</span>
                    <div>
                        <div class="text-sm"><span class="font-semibold">E-mail:</span> demo@exemplo.com</div>
                        <span class="text-sm"><span class="font-semibold">Senha:</span> demo1234</span>
                    </div>
                </div>
            @endif

            <form class="space-y-4" wire:submit="login">
                <!-- Email Address -->
                <x-input label="E-mail" type="email" wire:model="email" autofocus autocomplete="username" inline icon="o-envelope" />

                <!-- Password -->
                <x-password label="Senha" wire:model="password" autocomplete="current-password" inline password-icon="o-lock-closed" password-visible-icon="o-lock-open" />

                <!-- Remember Me -->
                <x-checkbox id="remember_me" label="Lembrar-me" wire:model="remember" />

                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    @endif

                    <x-button label="Entrar" type="submit" class="btn-primary" icon="o-paper-airplane" />
                </div>
            </form>
        </x-card>

        <div class="w-96 flex justify-between items-center mt-3">
            <x-button label="Principal" icon="o-chevron-left" link="{{ route('home') }}" class="btn-ghost text-white btn-sm" />

            @if (Route::has('register'))
                <x-button label="Não tem uma conta? Cadastre-se" link="{{ route('register') }}" class="btn-ghost text-white btn-sm mt-3" />
            @endif
        </div>
    </div>
</div>