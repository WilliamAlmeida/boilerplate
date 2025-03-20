<div class="flex justify-between mb-2">
    <div class="flex gap-x-5">
        <x-button label="Hoje" wire:click="goToCurrentWeek" class="btn-outline btn-sm rounded-2xl" />
        <div class="flex items-center gap-x-1">
            <x-button icon="o-chevron-left" wire:click="goToPreviousWeek" class="btn-ghost btn-sm btn-circle" />
            <x-button icon="o-chevron-right" wire:click="goToNextWeek" class="btn-ghost btn-sm btn-circle" />
        </div>
        <h2 class="text-2xl">
            {{ __($this->startsAt->format('M')).' '.$this->startsAt->format('Y') }}
            <span class="text-base font-semibold">/ {{ $this->startsAt->format('d') }} {{ __('to') }} {{ $this->endsAt->format('d') }}</span>
            @if($this->startsAt->format('M') != $this->endsAt->format('M'))
                <span class="text-base font-semibold">/</span> {{ __($this->endsAt->format('M')).' '.$this->endsAt->format('Y') }}
            @endif
        </h2>
    </div>
    
    <x-button label="Filtros" @click="$dispatch('drawer', true)" responsive icon="o-funnel" class="btn-primary btn-sm" />

     <!-- FILTER DRAWER -->
     <x-drawer wire:model="drawer" title="Filtros" right separator with-close-button class="lg:w-1/3" @drawer.window="$wire.drawer = $event.detail">
        <div class="space-y-2" x-data="{
            type: $wire.entangle('type'),
        }">
            {{-- <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" /> --}}
            <x-choices label="Status" wire:model="status" :options="$appointment_status" optionValue="id" placeholder="Selecione uma opção" single  />

            <x-radio label="Tipo" wire:model="type" :options="[['id' => 'c', 'name' => 'Exames'],['id' => 'p', 'name' => 'Especialistas']]" />

            <div x-show="type == 'c'">
                <x-choices label="Exame" wire:model="exam_id" :options="$exams" optionValue="id" optionLabel="title" placeholder="Selecione uma opção" single clearable  />
            </div>
            <div x-show="type == 'p'">
                <x-choices label="Especialista" wire:model="specialist_id" :options="$specialists" optionValue="id" optionLabel="title" placeholder="Selecione uma opção" single clearable />
            </div>
        </div>
 
         <x-slot:actions>
             <x-button label="Resetar" icon="o-x-mark" wire:click="clear" spinner />
             <x-button label="Feito" icon="o-check" class="btn-primary" @click="$dispatch('calendar:refresh'); $wire.drawer = false" />
         </x-slot:actions>
     </x-drawer>
</div>