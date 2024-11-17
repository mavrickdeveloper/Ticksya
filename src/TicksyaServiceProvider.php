<?php

namespace Ticksya;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ticksya\Commands\TicksyaCommand;
use Filament\Panel;
use Filament\PanelProvider;

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

        // Register migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'ticksya-migrations');

            $this->publishes([
                __DIR__ . '/../config/ticksya.php' => config_path('ticksya.php'),
            ], 'ticksya-config');
        }

        // Register assets if needed
        FilamentAsset::register([
            // Your assets here
        ], 'ticksya');
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        // Register the plugin with Filament 3
        $this->app->resolving(Panel::class, function (Panel $panel) {
            $panel
                ->id('ticksya')
                ->default()
                ->plugin(new TicksyaPlugin());
        });
    }
}
