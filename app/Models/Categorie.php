<?php

namespace App\Models;

use Closure;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
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

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Nombre')
                ->columnSpanFull()
                ->required()
                ->maxLength(255)
                ->live(debounce: 1000)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str($state)->slug())),
            Hidden::make('slug')
                ->required()
                ->live(),
            Toggle::make('status')
                ->label('Estado')
                ->default(true),
            Actions::make([
                Action::make('star')
                    ->label('Rellenar')
                    ->action(function ($livewire) {
                        $data = Categorie::factory()->make()->toArray();
                        $data['slug'] = str($data['name'])->slug();
                        $livewire->form->fill($data);
                    }),
            ]),
        ];
    }

    public function getColor(): string
    {
        return match ($this) {
            1 => 'info',
            0 => 'danguerous',
        };
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
