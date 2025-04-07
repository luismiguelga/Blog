<?php

namespace App\Models;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
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
        'status',
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
            Section::make('')
                ->columns(1)
                ->schema([TextInput::make('name')
                    ->label(__('labels.name'))
                    ->columnSpanFull()
                    ->required(false)
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    Group::make()
                        ->columns(2)
                        ->schema([
                            Toggle::make('status')
                                ->label(__('labels.status'))
                                ->default(true),
                            Actions::make([
                                Action::make('star')
                                    ->label(__('labels.star'))
                                    ->action(fn (Set $set) => $set('name', fake()->word())),
                            ]), ]),
                ]),

        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
