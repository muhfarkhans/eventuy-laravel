<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\OrganizationResource\Pages;
use App\Filament\Resources\Admin\OrganizationResource\RelationManagers;
use App\Infolists\Components\ThumbnailEntry;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->columnSpan(2)
                    ->required(),
                Textarea::make('address')
                    ->label('Address')
                    ->columnSpan(2)
                    ->rows(5)
                    ->autosize(),
                RichEditor::make('description')
                    ->label('Description')
                    ->columnSpan(2)
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->columnSpan(2)
                    ->required(),
                TextInput::make('organization_type')
                    ->label('Organization Type')
                    ->required(),
                TextInput::make('key_activities')
                    ->label('Key Activities')
                    ->required(),
                FileUpload::make('thumbnail')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('thumbnail'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->weight(FontWeight::Bold)
                    ->size(TextEntrySize::Large)
                    ->hiddenLabel()
                    ->columnSpanFull(),
                ThumbnailEntry::make('thumbnail')
                    ->hiddenLabel()
                    ->columnSpanFull(),
                Grid::make(3)
                    ->schema([
                        Section::make()
                            ->schema([
                                TextEntry::make('email')
                                    ->weight(FontWeight::SemiBold),
                            ])
                            ->columnSpan(1),
                        Section::make()
                            ->schema([
                                TextEntry::make('organization_type')
                                    ->weight(FontWeight::SemiBold),
                            ])
                            ->columnSpan(1),
                        Section::make()
                            ->schema([
                                TextEntry::make('key_activities')
                                    ->badge()
                                    ->color('primary'),
                            ])
                            ->columnSpan(1),
                    ]),
                TextEntry::make('description')
                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString($state))
                    ->hiddenLabel()
                    ->columnSpanFull()
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'view' => Pages\ViewOrganization::route('/{record}'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
