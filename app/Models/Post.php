<?php

namespace App\Models;

use App\Enums\Status;
use App\Observers\PostObserver;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([PostObserver::class])]
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
        'cover',
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
        'status' => Status::class,
        'category_id' => 'integer',
    ];

    public function tags(): BelongsToMany
    {
        return $this->BelongsToMany(Tag::class);
    }

    public function category(): BelongsTo
    {
        return $this->BelongsTo(Category::class);
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

        return [
            Section::make('')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label(__('labels.titulo'))
                        ->required()
                        ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule) {
                            return $rule->where('user_id', Auth::user()->id);
                        })
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
                        ->default(Auth::user()->id),
                    Select::make('category_id')
                        ->label(__('labels.category'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm(Category::getForm())
                        ->createOptionUsing(function ($data) {
                            $category = Category::create($data);

                            return $category->id;
                        })
                        ->options(Category::where('status', 1)->where('user_id', Auth::user()->id)->get()->pluck('name', 'id')),
                    CheckboxList::make('tags')
                        ->relationship('tags', 'name')
                        ->columnSpanFull()
                        ->columns(3)
                        ->bulkToggleable()
                        ->options(Tag::where('status', 1)->where('user_id', Auth::user()->id)->get()->pluck('name', 'id'))
                        ->searchable(),
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
                        ->directory(fn () => 'user-'.Auth::user()->id.'/posts')
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
