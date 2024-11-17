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
                Resources\TicketResource::class,
            ])
            ->pages([
                Pages\NotificationPreferences::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}