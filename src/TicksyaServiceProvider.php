<?php

namespace Ticksya;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ticksya\Commands\TicksyaCommand;

class TicksyaServiceProvider extends PackageServiceProvider
{

    public static string $name = 'ticksya';
    public function register(): void
    {
        
        //Register generate command
        $this->commands([
            TicksyaCommand::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/ticksya.php', 'ticksya');

        //Publish Config
        $this->publishes([
            __DIR__.'/../config/ticksya.php' => config_path('ticksya.php'),
        ], 'ticksya-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'ticksya-migrations');

        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ticksya');

        //Publish Views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/ticksya'),
        ], 'ticksya-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ticksya');

        //Publish Lang
        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('lang/vendor/ticksya'),
        ], 'ticksya-lang');

        $this->app->bind('ticksya', function () {
            return new \Ticksya\Services\TicksyaServices();
        });
    }

    public function boot(): void
    {
        // Register the plugin with Filament
        $this->app->afterResolving('filament', function ($filament) {
            $filament->registerPlugin(\Ticksya\TicksyaPlugin::make());
        });
    }
}
