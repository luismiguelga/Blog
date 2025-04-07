<?php

namespace App\Models;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'post_id' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getForm()
    {
        return [
            RichEditor::make('content')
                ->label(__('labels.content'))
                ->required()
                ->columnSpanFull()
                ->maxLength(255)
                ->disableToolbarButtons(['attachFiles', 'link']),
            Hidden::make('user_id')
                ->required()
                ->default(Auth::user()->id),
            // Hidden::make('post_id')
            //     ->required()
            //     ->default($post),
            Hidden::make('status')
                ->required()
                ->default(false)
        ];
    }
}
