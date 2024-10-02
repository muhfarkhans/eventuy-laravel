<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\OrganizationResource\Pages\ViewOrganization;
use App\Filament\Resources\Admin\UserResource\Pages;
use App\Filament\Resources\Admin\UserResource\RelationManagers;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNot('id', auth()->user()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->columnSpan(2)
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->columnSpan(2)
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->hidden(fn(string $operation): bool => $operation === 'create'),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->columnSpan(2)
                    ->unique(ignoreRecord: true)
                    ->required(),
                DatePicker::make('birthdate')
                    ->label('Birthdate')
                    ->columnSpan(2)
                    ->required(),
                Select::make('gender')
                    ->options([
                        'm' => 'Male',
                        'f' => 'Female'
                    ])
                    ->columnSpan(2),
                Textarea::make('address')
                    ->label('Address')
                    ->columnSpan(2)
                    ->rows(5)
                    ->autosize(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpan(2),
                Select::make('organizations')
                    ->relationship('organizations', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
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
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TagsColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->roles->pluck('name')->implode(',');
                    })
                    ->separator(','),
                TagsColumn::make('organizations.name')
                    ->label('Organization')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->organizations->pluck('name');
                    }),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
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
                    ->size(TextEntrySize::Large)
                    ->weight(FontWeight::Bold)
                    ->columnSpan(2),
                TextEntry::make('email')
                    ->icon('heroicon-m-envelope')
                    ->iconPosition(iconPosition: IconPosition::After)
                    ->columnSpan(2),
                TextEntry::make('birthdate')
                    ->default('-')
                    ->date(),
                TextEntry::make('gender'),
                TextEntry::make('address')
                    ->default('-')
                    ->columnSpan(2),
                RepeatableEntry::make('organizations')
                    ->hidden(fn(User $record): bool => in_array('user', $record->roles->pluck('name')->toArray()))
                    ->schema([
                        TextEntry::make('name')
                            ->weight(FontWeight::Bold)
                            ->url(fn(Organization $record): string => ViewOrganization::getUrl(['record' => $record])),
                        TextEntry::make('pivot.created_at')
                            ->label('Assign at')
                            ->color('gray'),
                        TextEntry::make('description')
                            ->columnSpan(2)
                            ->formatStateUsing(fn(string $state): HtmlString => new HtmlString($state))
                            ->limit(200),
                    ])
                    ->grid(2)
                    ->columnSpan(2),
                TextEntry::make('created_at')
                    ->label('Created at')
                    ->badge()
                    ->color('success'),
                TextEntry::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('primary')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
