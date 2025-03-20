<div>
    <!-- HEADER -->
    <x-header title="Minha Conta" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Editar" icon="o-pencil" class="btn-primary" link="{{ route('panel.account.edit') }}" />
        </x-slot:actions>
    </x-header>

    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
        <x-alert title="Seu endereço de e-mail não foi verificado." icon="o-exclamation-triangle" class="alert-warning mb-4" shadow>
            <x-slot:actions>
                <x-button label="Clique aqui para reenviar o e-mail de verificação." class="btn-sm" :link="route('verification.send')" />
            </x-slot:actions>
        </x-alert>
    @endif

    <!-- TABLE  -->
    <x-card>
        <div class="relative overflow-x-auto rounded-t-lg">

            <table class="table power-grid-table min-w-full">
                <tbody class="*:border-b *:border-pg-primary-100 *:dark:border-pg-primary-600 *:hover:bg-pg-primary-50 *:dark:bg-pg-primary-800 *:dark:hover:bg-pg-primary-700">
                    <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            Nome
                        </th>
                        <td class="px-2 py-2 text-right">
                            {{ $user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            E-mail
                        </th>
                        <td class="px-2 py-2 text-right">
                            {{ Str::lower($user->email) }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            Tipo de Usuário
                        </th>
                        <td class="px-2 py-2 text-right">
                            {{ $user->getTypeUser() }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            Assigned Roles @if(tenant()) Referring to the Condominium @endif
                        </th>
                        <td class="px-2 py-2 text-right">
                            @if($user->roles->count())
                                {{ Str::title($user->getRoleNames()->implode(', ')) }}
                            @else
                                No roles assigned.
                            @endif
                        </td>
                    </tr> --}}
                    <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            Timezone
                        </th>
                        <td class="px-2 py-2 text-right">
                            {{ $user->timezone }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            Registrado em
                        </th>
                        <td class="px-2 py-2 text-right">
                            {{ $user->created_at->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="px-2 py-2 font-medium whitespace-nowrap text-left">
                            Última Atualização
                        </th>
                        <td class="px-2 py-2 text-right">
                            {{ $user->updated_at->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-card>
</div>
