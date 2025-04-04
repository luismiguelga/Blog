<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Filament\Resources\PostRelationManagerResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Post::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->label('')
                    ->defaultImageUrl(function ($record) {
                        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='.urlencode($record->name);
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Publicación')
                    ->searchable()
                    ->sortable()
                    ->description(function (Post $record) {
                        return Str::limit($record->description, 40);
                    }),

                // Tables\Columns\TextColumn::make('slug'),
                // Tables\Columns\TextColumn::make('status')->badge(),
                // Tables\Columns\TextColumn::make('body')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable()
                    ->color(function ($state) {
                        return Status::from($state)->getColor();
                    })
                    ->searchable(),
                // Tables\Columns\TextColumn::make('categories.name')
                //     ->label('Categoria')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('date_publish')
                    ->label('Fecha de publicación')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordUrl(
                fn (Post $record) => $record->user_id === Auth::id()
                    ? static::getUrl('view', ['record' => $record->slug])
                    : null
            )
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Post $record) => static::getUrl('edit', ['record' => $record->slug]))
                    ->visible(function ($record) {
                        return $record->user->id == Auth::user()->id;
                    }),
                Tables\Actions\ViewAction::make()
                    ->url(fn (Post $record) => static::getUrl('view', ['record' => $record->slug]))
                    ->visible(function ($record) {
                        if ($record->user->id == Auth::user()->id && $record->status === 'Privado') {
                            return true;
                        } elseif ($record->status === 'Publico') {
                            return true;
                        }

                        return false;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Group::make(),
                Section::make('Informacion del post')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('cover')
                            ->label(''),
                        Group::make()
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Titulo'),
                                TextEntry::make('date_publish')
                                    ->label('Fecha de publicación'),
                            ]),
                        TextEntry::make('description')
                            ->label('Descripción'),
                        Section::make('')
                            ->schema([
                                TextEntry::make('body')
                                    ->label('Cuerpo')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getRecordRouteKeyName(): string
    {
        return 'slug';
    }

    public static function getNavigationLabel(): string
    {
        return 'Publicación';
    }

    public static function getLabel(): string
    {
        return 'publicación';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Publicaciones';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'view' => Pages\ViewPost::route('/{record}/view'),
            // 'view' => Pages\ViewPost::route('/{record}/' . Str::slug($consulta) . '/view'),
        ];
    }
}
