<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategorieResource\Pages;
use App\Models\Categorie;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategorieResource extends Resource
{
    protected static ?string $model = Categorie::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Categorie::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('labels.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estados')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Activo' : 'Pendiente')
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('modules.categories.plural_name');
    }

    public static function getLabel(): string
    {
        return __('modules.categories.singular_name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('modules.categories.plural_name');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategorie::route('/create'),
            'edit' => Pages\EditCategorie::route('/{record}/edit'),
        ];
    }
}
