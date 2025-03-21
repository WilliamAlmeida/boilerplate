<x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">
    {{-- BRAND --}}
    <div class="pt-3 flex justify-center items-center gap-3 select-none">
        {{-- <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="w-auto h-16" /> --}}
        <span class="font-bold text-lg text-primary dark:text-primary drop-shadow-sm" style="--tw-drop-shadow: drop-shadow(0 1px 1px);" x-show="!collapsed">
            <x-icon name="c-document-currency-dollar" class="w-8 h-8" />
            {{ config('app.name') }}
        </span>
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
            {{-- <x-menu-item title="Perfíl Profissional" icon="o-document-text" link="{{ route('panel.account.colaborador.edit') }}" /> --}}
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
        <x-theme-toggle />
    </div>
</x-slot:sidebar>