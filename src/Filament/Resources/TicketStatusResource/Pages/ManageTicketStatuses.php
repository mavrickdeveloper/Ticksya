<?php

namespace Ticksya\Filament\Resources\TicketStatusResource\Pages;

use Ticksya\Filament\Resources\TicketStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTicketStatuses extends ManageRecords
{
    protected static string $resource = TicketStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
