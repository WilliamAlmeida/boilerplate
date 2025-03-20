<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reinit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reinit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('cache:clear');

        $this->call('migrate:refresh');

        $this->call('db:seed');
    }
}
