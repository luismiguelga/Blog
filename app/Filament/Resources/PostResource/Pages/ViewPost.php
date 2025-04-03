<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected static ?string $title = 'Publicaciones';

    protected function getHeaderActions(): array
    {

        return[
            Actions\EditAction::make()
            ->form(Comment::getForm()),
        ];
    }
}
