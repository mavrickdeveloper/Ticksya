<?php

namespace Ticksya\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class NotificationPreferences extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static string $view = 'ticksya::filament.pages.notification-preferences';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = auth()->user()->getTicketNotificationPreferences();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Email Notification Preferences')
                    ->description('Choose which notifications you want to receive via email.')
                    ->schema([
                        Forms\Components\Toggle::make('ticket_created')
                            ->label('New Ticket Created')
                            ->helperText('Receive notifications when new tickets are created'),

                        Forms\Components\Toggle::make('ticket_assigned')
                            ->label('Ticket Assigned')
                            ->helperText('Receive notifications when tickets are assigned to you'),

                        Forms\Components\Toggle::make('ticket_status_changed')
                            ->label('Status Changed')
                            ->helperText('Receive notifications when ticket status changes'),

                        Forms\Components\Toggle::make('ticket_commented')
                            ->label('New Comments')
                            ->helperText('Receive notifications when new comments are added'),

                        Forms\Components\Toggle::make('ticket_due_soon')
                            ->label('Due Date Approaching')
                            ->helperText('Receive notifications when tickets are approaching their due date'),
                    ])
                    ->columns(2),
            ]);
    }

    public function submit(): void
    {
        auth()->user()->updateTicketNotificationPreferences($this->data);

        Notification::make()
            ->title('Notification preferences updated successfully')
            ->success()
            ->send();
    }
}
