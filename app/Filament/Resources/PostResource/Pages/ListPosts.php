<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Enums\Status;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Borradores' => Tab::make('Borradores')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::DRAFT);
                }),
            'Públicas' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::PUBLIC);
                }),
            'Privadas' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::PRIVATE);
                }),
        ];
    }
}
