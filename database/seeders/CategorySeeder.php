<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name' => 'Category 1',
                'slug' => 'category-1',
                'status' => true,
            ],
            [
                'name' => 'Category 2',
                'slug' => 'category-2',
                'status' => true,
            ],
            [
                'name' => 'Category 3',
                'slug' => 'category-3',
                'status' => true,
            ],
            [
                'name' => 'Category 4',
                'slug' => 'category-4',
                'status' => true,
            ],
            [
                'name' => 'Category 5',
                'slug' => 'category-5',
                'status' => true,
            ],
        ];
        foreach ($values as $value) {
            \App\Models\Category::create([
                'name' => $value['name'],
                'slug' => $value['slug'],
                'status' => $value['status'],
                'user_id' => User::class::all()->random()->id,
            ]);
        }
    }
}
