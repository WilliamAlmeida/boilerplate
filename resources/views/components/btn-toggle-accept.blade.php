@props([
    'data',
])

@if($data->accept)
    <x-button icon="o-check" wire:click="toggleAccept('{{ $data->id }}')" spinner class="btn-ghost btn-sm text-green-500" />
@else
    <x-button icon="o-x-mark" wire:click="toggleAccept('{{ $data->id }}')" spinner class="btn-ghost btn-sm text-red-500" />
@endif