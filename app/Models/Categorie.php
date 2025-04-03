<?php

namespace App\Models;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            ->maxLength(255),
            // TextInput::make('slug')
            // ->required()
            // ->maxLength(255),
            Toggle::make('status')
            ->label('Estado'),
        ];
    }

    public function getColor(): string
    {
        return match ($this) {
            1 => 'info',
            0 => 'danguerous',
        };
    }
}
