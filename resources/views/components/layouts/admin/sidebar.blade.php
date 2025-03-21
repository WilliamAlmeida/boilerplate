<x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">
    {{-- BRAND --}}
    <div class="pt-3 flex justify-center items-center gap-3 select-none">
        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="w-auto h-16" />
        {{-- <span class="font-bold text-lg text-red-800 dark:text-red-700 drop-shadow-sm" style="--tw-drop-shadow: drop-shadow(0 1px 1px);" x-show="!collapsed">{{ config('app.name') }}</span> --}}
    </div>

    {{-- MENU --}}
    <x-menu activate-by-route>

        {{-- User --}}
        @if($user = auth()->user())
            <x-menu-separator />

            <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                <x-slot:actions>
                    <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="sair" no-wire-navigate link="/logout" />
                </x-slot:actions>
            </x-list-item>

            <div x-show="collapsed" class="text-center" x-cloak>
                <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip="sair" no-wire-navigate link="/logout" />
            </div>

            <x-menu-separator />

            <x-menu-item title="Início" icon="o-home" link="{{ route('home') }}" />
            <x-menu-item title="Minha Conta" icon="o-user" link="{{ route('panel.account.view') }}" />

            @can('clientes.viewAny')
                <x-menu-item title="Clientes" icon="o-user-group" link="{{ route('panel.admin.clientes.index') }}" />
            @endcan
            @can('contratos.viewAny')
                <x-menu-item title="Contratos" icon="o-document-currency-dollar" link="{{ route('panel.admin.contratos.index') }}" />
            @endcan
            @can('financiamentos.viewAny')
                <x-menu-item title="Financiamentos" icon="o-currency-dollar" link="{{ route('panel.admin.financiamentos.index') }}" />
            @endcan

            @if($user->isAdmin())
                @role('super admin|admin')
                <x-menu-sub title="Configurações do Sistema" icon="c-cog">
                    @can('usuarios.viewAny')
                        <x-menu-item title="Usuários" icon="o-users" link="{{ route('panel.admin.accounts.index') }}" />
                    @endcan
                    @can('roles.viewAny')
                        <x-menu-item title="Funções" icon="o-cog" link="{{ route('panel.admin.roles.index') }}" />
                    @endcan
                    @can('permissions.viewAny')
                        <x-menu-item title="Permissões" icon="o-cog" link="{{ route('panel.admin.permissions.index') }}" />
                    @endcan
                </x-menu-sub>
                @endrole

                <x-menu-item title="Componentes" icon="s-squares-plus" link="{{ route('components-maryui.index') }}" />
            @endif
        @else
            <x-menu-separator />

            <x-menu-item title="Início" icon="o-home" link="{{ route('home') }}" />
            @if(Route::has('login'))
                <x-menu-item title="Entrar" icon="o-arrow-right-end-on-rectangle" link="{{ route('login') }}" />
            @endif
            @if(Route::has('register'))
                <x-menu-item title="Registrar" icon="phosphor.user-plus" link="{{ route('register') }}" />
            @endif

            <x-menu-separator />
        @endif
    </x-menu>

    {{-- THEME TOGGLE --}}
    <div class="mr-5 pt-5 text-center" :class="collapsed ? 'hidden' : ''">
        <x-change-theme class="my-3 mx-auto max-w-40" />
        <x-theme-toggle />
    </div>
</x-slot:sidebar>