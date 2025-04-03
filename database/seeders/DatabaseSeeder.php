<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Post::factory(10)->create();
        Tag::factory(10)->create();
        Categorie::factory(10)->create();

        User::factory()->create([
            'name' => 'luis',
            'email' => 'luismigalvis99@gmail.com',
            'password' => '123'
        ]);
    }
}
