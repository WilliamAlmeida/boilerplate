@props([
    'data',
])

<x-button icon="o-trash"  x-on:click="$wire.dispatch('dialog', {
    type: 'error',
    title: 'Deseja deletar este registro? ',
    description: 'Esta ação não poderá ser desfeita.',
    cancelText: 'Cancelar',
    confirmOptions: { text: 'Deletar', method: 'delete', params: { id: {{ $data->id }} } }
})" spinner class="btn-ghost btn-sm text-red-500" />