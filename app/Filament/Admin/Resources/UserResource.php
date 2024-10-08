<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrganizationResource\Pages\ViewOrganization;
use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Filament\Admin\Resources\UserResource\Pages\ViewUser;
use App\Models\Organization;
use App\Models\User;
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
use Illuminate\Database\Eloquent\Builder;
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
