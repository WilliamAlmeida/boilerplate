<?php

namespace App\Livewire\Public\Account;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

#[Layout('components.layouts.public')]
#[Title('Minha Conta')]
class AccountView extends Component
{
    #[Locked]
    public User $user;

    public function mount()
    {
        foreach(\App\Models\User::withTrashed()->get() as $user) {
            $user->update(['created_at' => $user->insert_time]);
        }

        $this->user = User::find(auth()->id());
    }

    public function render()
    {
        return view('livewire.public.account.account-view');
    }
}
