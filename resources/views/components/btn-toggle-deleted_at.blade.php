@props([
    'data',
])

@if(!$data->deleted_at)
    <x-button icon="o-eye" wire:click="toggleDelete('{{ $data->id }}')" spinner class="btn-ghost btn-sm text-green-500" />
@else
    <x-button icon="o-eye-slash" wire:click="toggleDelete('{{ $data->id }}')" spinner class="btn-ghost btn-sm text-red-500" />
@endif