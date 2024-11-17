<?php

namespace Ticksya\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ticksya\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ticksya\Filament\Resources\TicketResource\Pages;
use Ticksya\Filament\Resources\TicketResource\Pages\ListTickets;
use Ticksya\Filament\Resources\TicketResource\Pages\CreateTicket;
use Ticksya\Filament\Resources\TicketResource\Pages\EditTicket;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Support Tickets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Ticket Information')
                            ->description('Enter the main ticket details')
                            ->icon('heroicon-o-ticket')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->autofocus(),
                                Forms\Components\RichEditor::make('description')
                                    ->required()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                        'codeBlock',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->columnSpan(['lg' => 2]),

                        Forms\Components\Section::make('Ticket Details')
                            ->description('Manage ticket properties')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('description'),
                                        Forms\Components\ColorPicker::make('color')
                                            ->required(),
                                    ]),

                                Forms\Components\Select::make('priority_id')
                                    ->relationship('priority', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->formatStateUsing(fn ($state) => [
                                        'label' => $state,
                                        'color' => match($state) {
                                            1 => 'success',
                                            2 => 'info', 
                                            3 => 'warning',
                                            4 => 'danger',
                                            default => 'gray',
                                        }
                                    ]),

                                Forms\Components\Select::make('status_id')
                                    ->relationship('status', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live(),

                                Forms\Components\Select::make('assigned_to')
                                    ->relationship('assignee', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('Assign To')
                                    ->suffixIcon('heroicon-m-user')
                                    ->placeholder('Select an agent'),

                                Forms\Components\DateTimePicker::make('due_date')
                                    ->label('Due Date')
                                    ->native(false)
                                    ->suffixIcon('heroicon-m-calendar')
                                    ->timezone(config('app.timezone')),

                                Forms\Components\Toggle::make('is_private')
                                    ->label('Private Ticket')
                                    ->helperText('Only visible to admins and assigned agent')
                                    ->default(false),
                            ])
                            ->columnSpan(['lg' => 1]),

                        Forms\Components\Section::make('Additional Information')
                            ->description('Extra ticket details and attachments')
                            ->icon('heroicon-o-paper-clip')
                            ->schema([
                                Forms\Components\TagsInput::make('tags')
                                    ->separator(',')
                                    ->suggestions([
                                        'bug',
                                        'feature',
                                        'enhancement',
                                        'documentation',
                                        'help-wanted',
                                    ]),

                                Forms\Components\FileUpload::make('attachments')
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->acceptedFileTypes(['image/*', '.pdf', '.doc', '.docx', '.zip'])
                                    ->directory('ticket-attachments')
                                    ->preserveFilenames()
                                    ->maxSize(5120)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('internal_notes')
                                    ->label('Internal Notes')
                                    ->helperText('Only visible to staff members')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(['lg' => 3]),
                    ])
                    ->columns(['lg' => 3]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('Ticket ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->category->color ?? 'gray'),

                Tables\Columns\TextColumn::make('priority.name')
                    ->sortable()
                    ->badge()
                    ->color(fn (Model $record): string => match ($record->priority->level) {
                        1 => 'success',
                        2 => 'info',
                        3 => 'warning',
                        4 => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status.name')
                    ->sortable()
                    ->badge(),

                Tables\Columns\ImageColumn::make('assignee.avatar')
                    ->label('Agent')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->assignee?->name ?? 'NA')),

                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => 
                        $record->due_date && $record->due_date->isPast() 
                            ? 'danger' 
                            : 'success'
                    ),

                Tables\Columns\IconColumn::make('is_private')
                    ->boolean()
                    ->label('Private')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),

                Tables\Filters\SelectFilter::make('priority')
                    ->relationship('priority', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignee', 'name')
                    ->preload()
                    ->label('Assigned To'),

                Tables\Filters\Filter::make('due_date')
                    ->form([
                        Forms\Components\DatePicker::make('due_from'),
                        Forms\Components\DatePicker::make('due_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '>=', $date),
                            )
                            ->when(
                                $data['due_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '<=', $date),
                            );
                    }),

                Tables\Filters\TernaryFilter::make('is_private')
                    ->label('Private Tickets'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('clone')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status_id')
                                ->label('Status')
                                ->options(fn () => \Ticksya\Models\TicketStatus::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn ($record) => $record->update(['status_id' => $data['status_id']]));
                        }),
                    Tables\Actions\BulkAction::make('assignTo')
                        ->icon('heroicon-o-user')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('Assign To')
                                ->options(fn () => \App\Models\User::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn ($record) => $record->update(['assigned_to' => $data['assigned_to']]));
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes();
    }
}
