<?php

namespace App\Models;

use App\Enums\Status;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use App\Models\Categorie;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'date_publish',
        'slug',
        'description',
        'body',
        'status',
        'user_id',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date_publish' => 'datetime',
        'user_id' => 'integer',
        'category_id' => 'integer',
    ];

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public static function getForm(): array
    {
        $date = Carbon::now();
        $user = Auth::user()->id;

        return [
            Section::make('')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Titulo')
                        ->required()
                        ->maxLength(255)
                        ->live(debounce: 1000)
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', str($state)->slug())),
                    TextInput::make('description')
                        ->label('Descripción')
                        ->required()
                        ->maxLength(255),
                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            Status::DRAFT->value => Status::DRAFT->getLabel(),
                            Status::PUBLIC->value => Status::PUBLIC->getLabel(),
                            Status::PRIVATE->value => Status::PRIVATE->getLabel(),
                        ])
                        ->required(),
                    Hidden::make('slug')
                        ->required()
                        ->live(),
                    Hidden::make('user_id')
                        ->required()
                        ->default($user),
                    Select::make('category_id')
                        ->label('Categoria')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm(Categorie::getForm())
                        ->createOptionUsing(function ($data) {
                            $data['slug'] = $data['name'];
                            $category = Categorie::create($data);
                            return $category->id;
                        })
                        ->options(Categorie::get()->where('status', 1)->pluck('name', 'id')),
                    DateTimePicker::make('date_publish')
                        ->label('Fecha de publicacion')
                        ->required()
                        ->default($date),
                    RichEditor::make('body')
                        ->label('Cuerpo')
                        ->columnSpanFull()
                        ->required()
                        ->maxLength(255),
                    Section::make('Imagen')
                        ->collapsed()
                        ->schema([
                            FileUpload::make('cover')
                                ->required()
                                ->maxSize(1024 * 1024 * 10)
                                ->label('Imagen de la publicación')
                                ->image()
                                ->imageEditor(),
                        ]),
                    Actions::make([
                        Action::make('star')
                            ->label('Rellenar')
                            ->action(function ($livewire) {
                                $data = Post::factory()->make()->toArray();
                                $data['user'] = Auth::user()->id;
                                $data['slug'] = str($data['title'])->slug();
                                $livewire->form->fill($data);
                            }),
                    ]),
                ]),
        ];
    }
}
