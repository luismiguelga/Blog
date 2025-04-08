<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'cover' => fake()->text(),
            'slug' => fake()->slug(),
            'description' => fake()->text(),
            'body' => fake()->text(),
            'status' => fake()->randomElement(Status::class),
            'user_id' => User::all()->random()->id,
            'category_id' => Category::where('user_id', Auth::user()->id)->get()->random()->id,
        ];
    }
}
