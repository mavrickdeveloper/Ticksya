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

        // Publish migrations with the correct tag
        $this->info('Publishing migrations...');
        $this->call('vendor:publish', [
            '--tag' => 'ticksya-migrations'
        ]);

        // Publish config
        $this->info('Publishing config...');
        $this->call('vendor:publish', [
            '--tag' => 'ticksya-config'
        ]);

        // Run migrations if requested
        if ($this->confirm('Would you like to run migrations now?', false)) {
            $this->info('Running migrations...');
            $this->call('migrate');
        } else {
            $this->info('Please remember to run migrations with: php artisan migrate');
        }

        $this->info('Ticksya has been installed!');

        return self::SUCCESS;
    }
}
