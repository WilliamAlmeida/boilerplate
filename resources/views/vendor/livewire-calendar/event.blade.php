<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white text-black border-l-[3px] border-l-{{ $event['color'] }} rounded-lg border py-2 px-3 shadow-md {{ !$event['cancelled_by'] ? 'cursor-pointer' : 'cursor-not-allowed select-none' }}">

    <p class="text-sm font-medium">
        {{ $event['date']->format('H:i') }}
        {{ $event['title'] }}
    </p>
    @if($event['description'])
        <p class="mt-2 text-xs">
            {{ $event['description'] }}
        </p>
    @endif
    @if($event['rescheduled_from'])
        <span class="mt-2 text-xs bg-gray-200 text-black px-1 pb-[2px] rounded-md border-l-[2px] border-l-{{ $event['color'] }}">
            Reagendamento
        </span>
    @elseif($event['cancelled_by'])
        <span class="mt-2 text-xs bg-gray-200 text-black px-1 pb-[2px] rounded-md border-l-[2px] border-l-{{ $event['color'] }}">
            Reagendado
        </span>
    @endif
</div>
