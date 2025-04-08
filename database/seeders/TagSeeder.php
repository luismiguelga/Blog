<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name' => 'Tag 1',
                'slug' => 'Tag-1',
                'status' => true,
            ],
            [
                'name' => 'Tag 2',
                'slug' => 'Tag-2',
                'status' => true,
            ],
            [
                'name' => 'Tag 3',
                'slug' => 'Tag-3',
                'status' => true,
            ],
            [
                'name' => 'Tag 4',
                'slug' => 'Tag-4',
                'status' => true,
            ],
            [
                'name' => 'Tag 5',
                'slug' => 'Tag-5',
                'status' => true,
            ],
        ];
        foreach ($values as $value) {
            \App\Models\Tag::create([
                'name' => $value['name'],
                'slug' => $value['slug'],
                'status' => $value['status'],
                'user_id' => User::class::all()->random()->id,
            ]);
        }
    }
}
