<?php

namespace App\Filament\Resources\Admin\EventTicketCategoryResource\Pages;

use App\Filament\Resources\Admin\EventTicketCategoryResource;
use App\Filament\Traits\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventTicketCategory extends CreateRecord
{
    use HasParentResource;

    protected static string $resource = EventTicketCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? static::getParentResource()::getUrl('event-ticket-categories.index', [
            'parent' => $this->parent,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data[$this->getParentRelationshipKey()] = $this->parent->id;

        return $data;
    }
}
