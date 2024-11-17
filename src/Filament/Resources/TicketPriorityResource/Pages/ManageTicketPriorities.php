<?php

namespace Ticksya\Filament\Resources\TicketPriorityResource\Pages;

use Ticksya\Filament\Resources\TicketPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTicketPriorities extends ManageRecords
{
    protected static string $resource = TicketPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
