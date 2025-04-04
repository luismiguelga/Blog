<?php

namespace App\Models;

use Closure;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class Categorie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function slug(): Attribute
    {
       return new Attribute(
                set: fn ($value) => Str::slug($value)
            );
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label(__('labels.name'))
                ->columnSpanFull()
                ->required(false)
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Toggle::make('status')
                ->label('Estado')
                ->default(true),
            Actions::make([
                Action::make('star')
                    ->label('Rellenar')
                    ->action(fn (Set $set) => $set('name', fake()->word())),
            ]),
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
