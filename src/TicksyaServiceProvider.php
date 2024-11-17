<?php

namespace Ticksya;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ticksya\Commands\TicksyaCommand;
use Ticksya\Testing\TestsTicksya;

class TicksyaServiceProvider extends PackageServiceProvider
{
    public static string $name = 'ticksya';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */

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
                'create_ticket_comments_table',
                'create_ticket_attachments_table'
            ])
            ->hasCommand(TicksyaCommand::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('mavrickdeveloper/ticksya');
            });
    }

    public function packageRegistered(): void
    {
        // Register the package
        parent::packageRegistered();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        // Register migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations')
        ], 'migrations');

        // Register the plugin with Filament
        $this->app->afterResolving(Panel::class, function (Panel $panel) {
            $panel->plugin(TicksyaPlugin::make());
        });

        // Register any assets
        FilamentAsset::register([
            // Your assets here
        ], 'ticksya');
    }
}
