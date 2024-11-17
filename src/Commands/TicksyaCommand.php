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
        
        $this->call('vendor:publish', [
            '--provider' => 'Ticksya\\TicksyaServiceProvider',
            '--tag' => ['ticksya-config', 'ticksya-migrations']
        ]);

        $this->info('Ticksya has been installed successfully!');
        $this->info('Please run: php artisan migrate');

        return self::SUCCESS;
    }
}
