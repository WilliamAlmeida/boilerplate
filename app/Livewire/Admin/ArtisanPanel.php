<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Traits\HelperActions;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Artisan;

class ArtisanPanel extends Component
{
    use HelperActions;

    public $myModalArtisan = false;

    #[Validate('required|min:3', as: 'comando')]
    public $command;
    public $parameters;

    #[Locked]
    public $output;

    public $suggestions = [
        'list', 'migrate', 'schedule:list', 'schedule:run', 'queue:work --once', 'optimize:clear', 'route:list', 'route:clear', 'config:clear', 'cache:clear', 'view:clear', 'event:clear',
        'icon:cache', 'icon:clear', 'optimize'
        // 'dict:list', 'dict:clear',
    ];

    public function mount()
    {
        $this->suggestions = collect($this->suggestions)->map(function($item) {
            return ['id' => $item, 'name' => $item];
        })->sortBy('name')->values();
    }

    #[On('openArtisanPanel')]
    public function openArtisanPanel()
    {
        if(!auth()->user()->isAdmin()) return;

        $this->myModalArtisan = true;

        $this->reset('command', 'parameters', 'output');
    }

    public function runCommand()
    {
        $this->reset('output');

        $this->validate();

        $params = explode(';', $this->parameters);
        if(empty($this->parameters)) $params = [];

        try {
            throw_unless(auth()->user()->isAdmin(), 'Only admin can use commands.');

            $commands = explode(';', $this->command);

            foreach ($commands as $command) {
                $command = Str::squish($command);

                // if(auth()->user()->email != 'superadmin@hotmail.com') {
                //     throw_if($command != 'list', "You can't use the command <strong>{$command}</strong>.");
                // }

                Artisan::call($command, $params);
            }

            $output = Artisan::output();
            if(!empty($output)) {
                $this->output = nl2br($output);
            }else{
                $this->output = 'The command executed with success.';
            }
        } catch (\Throwable $th) {
            //throw $th;

            $this->output = $th->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.artisan-panel');
    }
}
