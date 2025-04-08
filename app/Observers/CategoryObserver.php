<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryObserver
{

    public function creating(Category $category): void
    {
        $category->slug = $this->handleSlug($category->name);
    }

    public function saving (Category $category): void
    {

        $category->slug = $this->handleSlug($category->name, $category->id);
    }

    protected function handleSlug(string $value, int $id = null): string
    {
        $slug = Str::slug($value);

        if (Auth::check()) {
            $callback = function (string $slug) use ($id) {
                return Category::where('slug', $slug)
                    ->where('user_id', Auth::user()->id)
                    ->where('id', '!=', $id)
                    ->exists();
            };

            $i = 1;

            $originalSlug = $slug;

            while ($callback($slug) || $i > 20) {

                $slug = $originalSlug . '-' . $i;
                $i++;
            }
        }

        return $slug;
    }
}
