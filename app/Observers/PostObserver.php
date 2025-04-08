<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post): void
    {
        $post->slug = $this->handleSlug($post->title);
    }

    public function saving (Post $post): void
    {
        $post->slug = $this->handleSlug($post->title, $post->id);
    }

    protected function handleSlug(string $value, int $id = null): string
    {
        $slug = Str::slug($value);

        if(Auth::check()) {
            $callback = function(string $slug)use ($id) {
                return Post::where('slug', $slug)
                    ->where('user_id', Auth::user()->id)
                    ->where('id', '!=', $id)
                    ->exists();
            };

            $i = 1;

            $originalSlug = $slug;

            while($callback($slug) || $i > 20) {

                $slug = $originalSlug . '-' . $i;
                $i++;
            }
        }

        return $slug;
    }
}
