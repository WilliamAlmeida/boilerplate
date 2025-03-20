<div class="bg-white text-black p-2 rounded shadow {{ !$item['cancelled_by'] ? 'cursor-grab' : 'cursor-not-allowed select-none' }}" wire:click="$dispatch('edit', ['{{ $item['id'] }}'])" card-id="{{ $item['id'] }}" wire:key="{{ $item['id'] }}">
    <div class="flex justify-between text-sm font-medium *:flex *:items-center *:gap-x-1 text-gray-400">
        @php($date = now()->parse($item['date']))
        <span>
            <x-icon name="o-calendar" />
            {{ $date->format('d/m/Y') }}
        </span>
        <span>
            <x-icon name="o-clock" class="" />
            {{ $date->format('H:i') }}
        </span>
    </div>
    <p class="text-sm font-medium">
        {{ $item['title'] }}
    </p>
    @if($item['description'])
        <p class="mt-2 text-xs">
            {{ $item['description'] }}
        </p>
    @endif
    @if($item['rescheduled_from'])
        <span class="mt-2 text-xs bg-gray-200 text-black px-1 pb-[2px] rounded-md border-l-[2px] border-l-{{ $colors[$status['id']] }}">
            Reagendamento
        </span>
    @elseif($item['cancelled_by'])
        <span class="mt-2 text-xs bg-gray-200 text-black px-1 pb-[2px] rounded-md border-l-[2px] border-l-{{ $colors[$status['id']] }}">
            Reagendado
        </span>
    @endif
</div>