<?php

namespace App\Livewire\Components;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.public')]
class ComponentsMaryUi extends Component
{
    use Toast;

    public $showDrawer = false;
    public $showDrawer2 = false;
    public $showDrawer3 = false;
    public $showDrawer4 = false;
    public $showDrawer5 = false;

    public $myModal = false;
    public $myModal2 = false;
    public $myModal3 = false;
    public $myModal4 = false;
    public $myModal5 = false;

    public $pin2 = '';

    public $selectedTab = 'users-tab';

    public $password = '';

    public $step = 3;

    public function triggerToast($type)
    {
        switch ($type) {
            case 'success':
                $this->success('We are done, check it out');
                break;
            case 'error':
                $this->error(
                    'It will last just 1 second ...',
                    timeout: 1000,
                    position: 'toast-bottom toast-start'
                );
                break;
            case 'warning_redirect':
                $this->warning(
                    'It is working with redirect',
                    'You were redirected to another url ...',
                    redirectTo: route('home')
                );
                break;
            case 'custom_warning':
                $this->warning(
                    'Wishlist <u>updated</u>',
                    'You will <strong>love it :)</strong>',
                    position: 'bottom-end',
                    icon: 'o-heart',
                    css: 'bg-pink-500 text-base-100'
                );
                break;
        }
    }

    public function render()
    {
        return view('livewire.components.components-mary-ui');
    }
}
