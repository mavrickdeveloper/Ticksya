<?php

namespace Ticksya\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TicksyaCommand extends Command
{
    public $signature = 'ticksya:install';

    public $description = 'Install and configure Ticksya';

    public function handle(): int
    {
        $this->info('Installing Ticksya...');
        
        // Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'Ticksya\\TicksyaServiceProvider',
            '--tag' => 'ticksya-migrations'
        ]);

        // Publish config
        $this->call('vendor:publish', [
            '--provider' => 'Ticksya\\TicksyaServiceProvider',
            '--tag' => 'ticksya-config'
        ]);

        // Run migrations
        if ($this->confirm('Would you like to run migrations now?', true)) {
            $this->info('Running migrations...');
            $this->call('migrate');
        }

        $this->info('Ticksya has been installed successfully!');
        
        if (!$this->confirm('Would you like to run migrations now?', true)) {
            $this->info('Please remember to run migrations with: php artisan migrate');
        }

        return self::SUCCESS;
    }
}
