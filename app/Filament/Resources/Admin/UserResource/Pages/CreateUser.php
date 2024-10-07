<?php

namespace App\Filament\Resources\Admin\UserResource\Pages;

use App\Filament\Resources\Admin\UserResource;
use App\Forms\Components\BadgeForm;
use App\Models\Role;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Hash;

class CreateUser extends CreateRecord
{
    use HasWizard;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.admin.user.pages.create-user';

    protected function getSteps(): array
    {
        return [
            Step::make('Profile Details')
                ->description("Enter the user's basic profile information.")
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
                        ->afterStateUpdated(function ($state, $set) {
                            $role_text = str_replace('_', '', Role::whereIn('id', $state)->pluck('name')->toArray());
                            $set('roles_text', $role_text);

                            if (count($state) == 1 && $state[0] == '3') {
                                $set('organizations', []);
                            }
                        })
                        ->columnSpan(2),
                    TextInput::make('roles_text')
                        ->hidden(),
                ])
                ->afterValidation(function (array $state) {
                    if (empty(array_intersect([1, 2], $state['roles']))) {
                        $this->dispatchFormEvent('wizard::nextStep', 'data', 2);
                    }
                }),
            Step::make('Choose Organization')
                ->disabled(fn($state): bool => empty (array_intersect([1, 2], $state['roles'])))
                ->description('Select an Organization')
                ->schema([
                    Select::make('organizations')
                        ->relationship('organizations', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->columnSpan(2),
                ]),
            Step::make('Review')
                ->description('Review and Confirm')
                ->schema([
                    Section::make('Form Summary')
                        ->description('Review data new user')
                        ->schema([
                            Placeholder::make('name')
                                ->content($this->data['name'] ?? '')
                                ->label('Name'),
                            Placeholder::make('email')
                                ->content($this->data['email'] ?? '')
                                ->label('Email'),
                            Placeholder::make('birthdate')
                                ->content($this->data['birthdate'] ?? '')
                                ->label('Birthdate'),
                            Placeholder::make('gender')
                                ->content(function () {
                                    if ($this->data['gender'] == 'f') {
                                        return 'Female';
                                    } else if ($this->data['gender'] == '') {
                                        return 'Male';
                                    } else {
                                        return '';
                                    }
                                })
                                ->label('Gender'),
                            Placeholder::make('address')
                                ->content($this->data['address'] ?? '')
                                ->label('Address'),
                            BadgeForm::make('roles_text')
                                ->label('Roles'),
                        ])
                ])
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->data;
        $data['password'] = bcrypt('password');

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole($this->record->roles);
    }
}
