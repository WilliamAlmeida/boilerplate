<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class DashboardIndex extends Component
{
    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.admin.dashboard.dashboard-index');
    }
}