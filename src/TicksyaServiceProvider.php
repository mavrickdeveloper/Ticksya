<?php

namespace Ticksya;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ticksya\Commands\TicksyaCommand;

class TicksyaServiceProvider extends PackageServiceProvider
{
    public static string $name = 'ticksya';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                'create_ticket_categories_table',
                'create_ticket_priorities_table',
                'create_ticket_statuses_table',
                'create_tickets_table',
                'add_notification_preferences_to_users_table',
            ])
            ->hasCommand(TicksyaCommand::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        // Register assets if needed
        FilamentAsset::register([
            // Your assets here
        ], 'ticksya');
    }
}
