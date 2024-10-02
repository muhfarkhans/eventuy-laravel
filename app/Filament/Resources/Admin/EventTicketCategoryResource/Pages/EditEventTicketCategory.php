<?php

namespace App\Filament\Resources\Admin\EventTicketCategoryResource\Pages;

use App\Filament\Resources\Admin\EventTicketCategoryResource;
use App\Filament\Traits\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventTicketCategory extends EditRecord
{
    use HasParentResource;

    protected static string $resource = EventTicketCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? static::getParentResource()::getUrl('event-ticket-categories.index', [
            'parent' => $this->parent,
        ]);
    }

    protected function configureDeleteAction(Actions\DeleteAction $action): void
    {
        $resource = static::getResource();

        $action->authorize($resource::canDelete($this->getRecord()))
            ->successRedirectUrl(static::getParentResource()::getUrl('event-ticket-categories.index', [
                'parent' => $this->parent,
            ]));
    }
}
