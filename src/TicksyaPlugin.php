<?php
namespace Ticksya;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Ticksya\Filament\Resources\TicketResource;
use Ticksya\Filament\Resources\TicketPriorityResource;

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
                TicketResource::class,
                TicketPriorityResource::class,
            ])
            ->navigationGroups([
                'Support Tickets'
            ]);
    }

    public function boot(Panel $panel): void
    {
        // Boot logic here
    }

    public static function make(): static
    {
        return app(static::class);
    }
}