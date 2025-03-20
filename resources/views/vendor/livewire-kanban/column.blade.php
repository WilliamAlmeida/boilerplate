<div class="bg-gray-100 p-4 rounded-lg shadow-md border-l-[3px] border-l-{{ $colors[$status['id']] }}" wire:key="{{ $status['id'] }}">
    <div class="flex flex-row mb-4 justify-between">
        <h2 class="text-lg text-black font-bold select-none">{{ $status['name'] }}</h2>
        <div class="flex justify-center items-center gap-x-3">
            <x-button icon="o-plus" class="btn-ghost text-primary btn-sm" wire:click="$dispatch('create')" />
            <x-icon name="o-bars-2" class="text-primary cursor-move" />
        </div>
    </div>
    <div class="space-y-2 min-h-20" group-id="{{ $status['id'] }}" x-data="" x-init="Sortable.create($el, {
        animation: 150,
        handle: '.cursor-grab',
        group: 'group',
        onEnd: function({ from, to, item }) {
            const newStatus = to.getAttribute('group-id');
            const oldStatus = from.getAttribute('group-id');

            if(newStatus === oldStatus) return;

            const cardId = item.getAttribute('card-id');
            @this.onEventDropped(cardId, newStatus);
        }
    })">
        @foreach ($values as $item)
            @include($this->eventView, ['item' => $item])
        @endforeach
    </div>

    @if($values->count() && $status['id'] == 'pending')
        <x-button label="Add Appointment" icon="o-plus" responsive class="mt-2 w-full btn-ghost text-primary btn-sm" wire:click="$dispatch('create')" />
    @endif
</div>