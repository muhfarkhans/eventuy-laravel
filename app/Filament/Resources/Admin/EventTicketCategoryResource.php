<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\EventTicketCategoryResource\Pages;
use App\Filament\Resources\Admin\EventTicketCategoryResource\Pages\ListEventTicketCategories;
use App\Filament\Resources\Admin\EventTicketCategoryResource\RelationManagers;
use App\Forms\Components\TextInputWithPreviewUrl;
use App\Models\EventTicketCategory;
use App\Tables\Columns\ArrayListColumn;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventTicketCategoryResource extends Resource
{
    protected static ?string $model = EventTicketCategory::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInputWithPreviewUrl::make('name')
                    ->default('Ticket title')
                    ->setPrependText('https://eventuy.com/event/ticket')
                    ->live()
                    ->required()
                    ->columnSpan(2),
                TextInput::make('price')
                    ->default(0)
                    ->live()
                    ->label('Price')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->required()
                    ->maxLength(12)
                    ->numeric(),
                TextInput::make('total_ticket')
                    ->label('Total Ticket')
                    ->required()
                    ->numeric(),
                Grid::make(2)
                    ->schema([
                        ColorPicker::make('color')
                            ->default('#334155')
                            ->live()
                            ->required()
                            ->columnSpan(1),
                        ColorPicker::make('color_secondary')
                            ->default('#fff')
                            ->live()
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),
                TagsInput::make('benefits')
                    ->placeholder('Type the benefit')
                    ->splitKeys(['Tab'])
                    ->live()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name'),
                TextColumn::make('name'),
                ArrayListColumn::make('benefits'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->url(
                        fn(ListEventTicketCategories $livewire, Model $record): string => static::$parentResource::getUrl('event-ticket-categories.edit', [
                            'record' => $record,
                            'parent' => $livewire->parent,
                        ])
                    ),
                DeleteAction::make()
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
            // 'index' => Pages\ListEventTicketCategories::route('/'),
            // 'create' => Pages\CreateEventTicketCategory::route('/create'),
            // 'edit' => Pages\EditEventTicketCategory::route('/{record}/edit'),
        ];
    }

    public static string $parentResource = EventResource::class;

    public static function getRecordTitle(?Model $record): string|null|Htmlable
    {
        return $record->title;
    }
}
