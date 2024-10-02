<?php

namespace App\Filament\Resources\Admin\EventTicketCategoryResource\Pages;

use App\Filament\Resources\Admin\EventTicketCategoryResource;
use App\Filament\Traits\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventTicketCategories extends ListRecords
{
    use HasParentResource;

    protected static string $resource = EventTicketCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(
                    fn(): string => static::getParentResource()::getUrl('event-ticket-categories.create', [
                        'parent' => $this->parent,
                    ])
                ),
        ];
    }
}
