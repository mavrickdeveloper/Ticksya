<?php

namespace Ticksya;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
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
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                'create_tickets_table',
                'create_ticket_categories_table',
                'create_ticket_priorities_table',
                'create_ticket_statuses_table',
                'create_ticket_comments_table',
                'create_ticket_attachments_table',
            ])
            ->hasCommand(TicksyaCommand::class);
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();
        
        // Register Assets
        FilamentAsset::register([
            // Add your assets here
        ], static::$name);
    }
}
