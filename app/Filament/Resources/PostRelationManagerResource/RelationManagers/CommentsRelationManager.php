<?php

namespace App\Filament\Resources\PostRelationManagerResource\RelationManagers;

use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema((new Comment)->getForm($this->getOwnerRecord()->id));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label(__('labels.content'))
                    ->html()
                    ->limit(30),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('labels.user_name')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label(__('labels.status'))
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            1 => 'Activo',
                            0 => 'Pendiente',
                        };
                    })
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        return $record->user->id == Auth::user()->id;
                    })->after(function () {
                        Notification::make()->success()->title('Success')
                            ->duration(1000)
                            ->body('Everyting is okey')
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        return $record->user->id == Auth::user()->id;
                    })->after(function () {
                        Notification::make()->success()->title('Success')
                            ->duration(1000)
                            ->body('Everyting is okey')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
