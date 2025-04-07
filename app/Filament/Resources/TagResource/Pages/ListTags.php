<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todas' => Tab::make(),
            'Activas' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', true);
                }),
            'Inactivas' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', false);
                }),
        ];
    }
}
