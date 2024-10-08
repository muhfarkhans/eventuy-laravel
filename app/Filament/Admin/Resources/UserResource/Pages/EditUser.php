<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\Gender;
use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
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
                    ->dehydrated(fn(?string $state): bool => filled($state)),
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
                    ->options(Gender::labels())
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles($this->record->roles);
    }
}
