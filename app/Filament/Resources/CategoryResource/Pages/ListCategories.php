<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

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
            'Pendientes' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', false);
                }),
        ];
    }
}
