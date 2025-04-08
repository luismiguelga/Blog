<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'luis',
            'email' => 'luismigalvis99@gmail.com',
            'password' => '123',
        ]);

        User::factory(10)->create([
            'password' => bcrypt('password'),
        ]);
    }
}
