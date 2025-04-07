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
use Illuminate\Validation\Rules\Enum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

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
                        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->name);
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Publicación')
                    ->searchable()
                    ->sortable()
                    ->description(function (Post $record) {
                        return Str::limit($record->description, 40);
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('labels.user_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('labels.status'))
                    ->badge()
                    ->sortable()
                    ->color(function ($state) {
                        return Status::from($state)->getColor();
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_publish')
                    ->label(__('labels.date_publish'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->label(__('labels.status'))
                    ->options([
                        Status::DRAFT->value => Status::DRAFT->getLabel(),
                        Status::PUBLIC->value => Status::PUBLIC->getLabel(),
                        Status::PRIVATE->value => Status::PRIVATE->getLabel(),
                    ])
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        return $record->user->id == Auth::user()->id;
                    }),
                Tables\Actions\ViewAction::make()
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
                Tables\Actions\BulkActionGroup::make([]),
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
                                    ->label(__('labels.titulo')),
                                TextEntry::make('date_publish')
                                    ->label(__('labels.date_publish')),
                            ]),
                        TextEntry::make('description')
                            ->label(__('labels.description')),
                        Section::make('')
                            ->schema([
                                TextEntry::make('body')
                                    ->label(__('labels.body'))
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

    public static function getNavigationLabel(): string
    {
        return __('modules.posts.plural_name');
    }

    public static function getLabel(): string
    {
        return __('modules.posts.singular_name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('modules.posts.plural_name');
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
