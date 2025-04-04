<?php

namespace App\Models;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
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
        'post_id' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Nombre')
                ->columnSpanFull()
                ->required()
                ->maxLength(255)
                ->live(debounce: 1000)
                ->afterStateUpdated(fn($state, callable $set) => $set('slug', str($state)->slug())),
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
                        $data = Tag::factory()->make()->toArray();
                        $data['slug'] = str($data['name'])->slug();
                        $livewire->form->fill($data);
                    }),
            ]),
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
