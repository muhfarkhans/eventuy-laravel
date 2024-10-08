<?php

namespace App\Filament\Admin\Resources\EventTicketCategoryResource\Pages;

use App\Filament\Admin\Resources\EventTicketCategoryResource;
use App\Traits\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventTicketCategory extends CreateRecord
{
    use HasParentResource;

    protected static string $resource = EventTicketCategoryResource::class;

    protected static string $view = 'filament.resources.admin.event-ticket-category.pages.create-event-ticket-category';

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? static::getParentResource()::getUrl('event-ticket-categories.index', [
            'parent' => $this->parent,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data[$this->getParentRelationshipKey()] = $this->parent->id;
        $data['benefits'] = json_encode($data['benefits']);

        return $data;
    }
}
