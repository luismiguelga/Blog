<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

#[ObservedBy([CategoryObserver::class])]
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
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('')
                ->columns(1)
                ->schema([
                    TextInput::make('name')
                        ->label(__('labels.name'))
                        ->columnSpanFull()
                        ->required()
                        ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule) {
                            return $rule->where('user_id', Auth::user()->id);
                        })
                        ->maxLength(255),
                    Hidden::make('user_id')
                        ->required()
                        ->default(Auth::user()->id),
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
                            ]),
                        ]),
                ]),

        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
