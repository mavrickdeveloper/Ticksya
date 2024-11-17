<?php
namespace Ticksya;

use Filament\Contracts\Plugin;
use Filament\Panel;

class TicksyaPlugin implements Plugin
{
    public function getId(): string
    {
        return 'ticksya';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                // Your resources here
            ])
            ->pages([
                // Your pages here
            ]);
    }

    public function boot(Panel $panel): void
    {
        // Boot logic here
    }

    // Recommended fluent instantiation method
    public static function make(): static
    {
        return app(static::class);
    }
}