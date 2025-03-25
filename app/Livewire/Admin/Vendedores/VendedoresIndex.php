<?php

namespace App\Livewire\Admin\Vendedores;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Vendedores;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Traits\PermissionTrait;
use Illuminate\Pagination\LengthAwarePaginator;

#[Title('Vendedores')]
class VendedoresIndex extends Component
{
    use Toast;
    use PermissionTrait;
    use WithPagination;
    private $resource = 'vendedores';

    public $perPage = 20;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'nome', 'direction' => 'asc'];

    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    #[On('delete')]
    public function delete($id): void
    {
        $model = Vendedores::withTrashed()->find($id);

        if(!$model->trashed()) {
            $this->warning('Desative o registro antes de deletar.', position: 'toast-top');
            return;
        }

        $model->forceDelete();

        $this->success('Registro deletado.', position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'nome', 'label' => 'Nome', 'format' => fn ($value) => Str::limit($value->nome, 50)],
            ['key' => 'created_at', 'label' => 'Registrado em', 'format' => fn ($value) => $value->created_at->format('d/m/Y - H:i')],
            ['key' => 'updated_at', 'label' => 'Atualizado em', 'format' => fn ($value) => $value->updated_at->diffForHumans()],
            ['key' => 'deleted_at', 'label' => 'Ativo', 'hidden' => !$this->permissions(['delete'])->delete],
        ];
    }

    #[On('table:refresh')]
    public function vendedores(): LengthAwarePaginator
    {
        return Vendedores::query()
            ->withTrashed()
            ->sortBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->search($this->search);
            })
            ->paginate($this->perPage);
    }

    public function toggleDelete($id): void
    {
        $vendedor = Vendedores::withTrashed()->find($id);

        if ($vendedor) {
            $vendedor->trashed() ? $vendedor->restore() : $vendedor->delete();

            $this->success('Vendedor atualizado.', position: 'toast-bottom');
        } else {
            $this->error('Vendedor não encontrado.', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.admin.vendedores.vendedores-index', [
            'vendedores' => $this->vendedores(),
            'headers' => $this->headers(),
            'can' => $this->permissions(),
        ]);
    }
}
