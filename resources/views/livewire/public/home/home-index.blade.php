<div>
    <!-- HEADER -->
    <x-header title="{{ config('app.name') }}" separator progress-indicator />

    @auth
        <livewire:components.statistics.contratos-stats />
    @endauth
</div>