<?php

namespace App\Models;

use App\Enums\Status;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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
use Filament\Forms\Set;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;


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
        return $this->belongsToMany(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function slug(): Attribute
    {
        return new Attribute(
            set: fn($value) => Str::slug($value)
        );
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
                        ->label(__('labels.titulo'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('description')
                        ->label(__('labels.description'))
                        ->required()
                        ->maxLength(255),
                    Select::make('status')
                        ->label(__('labels.status'))
                        ->options([
                            Status::DRAFT->value => Status::DRAFT->getLabel(),
                            Status::PUBLIC->value => Status::PUBLIC->getLabel(),
                            Status::PRIVATE->value => Status::PRIVATE->getLabel(),
                        ])
                        ->required(),
                    Hidden::make('user_id')
                        ->required()
                        ->default($user),
                    Select::make('category_id')
                        ->label(__('labels.category'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm(Category::getForm())
                        ->createOptionUsing(function ($data) {
                            $data['slug'] = $data['name'];
                            $category = Category::create($data);

                            return $category->id;
                        })
                        ->options(Category::get()->where('status', 1)->pluck('name', 'id')),
                    DateTimePicker::make('date_publish')
                        ->label(__('labels.date_publish'))
                        ->required()
                        ->default($date),
                    RichEditor::make('body')
                        ->label(__('labels.body'))
                        ->columnSpanFull()
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('cover')
                        ->required()
                        ->columnSpanFull()
                        ->maxSize(1024 * 1024 * 10)
                        ->label(__('labels.cover'))
                        ->image()
                        ->imageEditor(),
                    Actions::make([
                        Action::make('star')
                            ->label(__('labels.star'))
                            ->action(function ($livewire) {
                                $data = Post::factory()->make()->toArray();
                                $data['user_id'] = Auth::user()->id;
                                $livewire->form->fill($data);
                            }),
                    ]),
                ]),
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
