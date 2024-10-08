<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Admin\Resources\EventResource\Pages\EditEvent;
use App\Filament\Admin\Resources\EventResource\Pages\ListEvents;
use App\Filament\Admin\Resources\EventTicketCategoryResource\Pages\CreateEventTicketCategory;
use App\Filament\Admin\Resources\EventTicketCategoryResource\Pages\EditEventTicketCategory;
use App\Filament\Admin\Resources\EventTicketCategoryResource\Pages\ListEventTicketCategories;
use App\Models\Event;
use App\Models\Organization;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('organization_id')
                    ->label('Organization')
                    ->searchable()
                    ->getSearchResultsUsing(
                        fn(string $search): array => Organization::where('name', 'like', "%{$search}%")
                            ->limit(5)
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn($value): ?string => Organization::find($value)?->name)
                    ->preload()
                    ->columnSpan(2),
                TextInput::make('name')
                    ->required()
                    ->columnSpan(2),
                RichEditor::make('description')
                    ->columnSpan(2),
                DateTimePicker::make('start_time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                TextInput::make('location_gmaps')
                    ->required()
                    ->url(),
                TextInput::make('total_ticket')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('organization.name'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('Manage category ticket')
                    ->color('primary')
                    ->icon('heroicon-m-tag')
                    ->url(
                        fn(Event $record): string => static::getUrl('event-ticket-categories.index', [
                            'parent' => $record->id,
                        ])
                    ),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),

            // event-ticket-categories
            'event-ticket-categories.index' => ListEventTicketCategories::route('{parent}/event-ticket-categories/'),
            'event-ticket-categories.create' => CreateEventTicketCategory::route('{parent}/event-ticket-categories/create'),
            'event-ticket-categories.edit' => EditEventTicketCategory::route('{parent}/event-ticket-categories/{record}/edit'),
        ];
    }
}
