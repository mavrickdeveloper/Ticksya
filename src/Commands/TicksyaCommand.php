<?php

namespace Ticksya\Commands;

use Illuminate\Console\Command;

class TicksyaCommand extends Command
{
    public $signature = 'ticksya:install';

    public $description = 'Install and configure Ticksya';

    public function handle(): int
    {
        $this->info('Installing Ticksya...');
        
        // Add installation logic here
        $this->call('vendor:publish', [
            '--provider' => 'Ticksya\\TicksyaServiceProvider',
            '--tag' => 'ticksya-config'
        ]);

        $this->info('Ticksya has been installed successfully!');

        return self::SUCCESS;
    }
}
