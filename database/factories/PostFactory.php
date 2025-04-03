<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\User;

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
            'date_publish' => fake()->dateTime(),
            'slug' => fake()->slug(),
            'description' => fake()->text(),
            'body' => fake()->text(),
            'status' => fake()->randomElement(Status::class),
            'user_id' => User::factory(),
            'category_id' => Categorie::factory(),
        ];
    }
}
