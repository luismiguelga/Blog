<?php

namespace App\Observers;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TagObserver
{
    public function creating(Tag $tag): void
    {
        $tag->slug = $this->handleSlug($tag->name);
    }

    public function saving (Tag $tag): void
    {

        $tag->slug = $this->handleSlug($tag->name, $tag->id);
    }

    protected function handleSlug(string $value, int $id = null): string
    {
        $slug = Str::slug($value);

        if(Auth::check()) {
            $callback = function(string $slug)use ($id) {
                return Tag::where('slug', $slug)
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
