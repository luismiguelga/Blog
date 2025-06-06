<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'title' => 'El futuro de la inteligencia artificial',
                'slug' => 'futuro-inteligencia-artificial',
                'cover' => 'covers/ai-future.jpg',
                'description' => 'Una mirada hacia lo que nos espera con la IA.',
                'body' => 'Contenido completo sobre el futuro de la IA...',
            ],
            [
                'title' => 'Guía completa de Laravel 11',
                'slug' => 'guia-laravel-11',
                'cover' => 'covers/laravel-11.jpg',
                'description' => 'Todo lo que necesitas saber sobre Laravel 11.',
                'body' => 'Contenido de la guía completa de Laravel 11...',
            ],
            [
                'title' => 'Tendencias en diseño web 2025',
                'slug' => 'tendencias-diseno-web-2025',
                'cover' => 'covers/design-trends.jpg',
                'description' => 'Descubre lo más actual en diseño web.',
                'body' => 'Contenido detallado de las tendencias...',
            ],
            [
                'title' => 'Cómo comenzar con React desde cero',
                'slug' => 'comenzar-react-desde-cero',
                'cover' => 'covers/react-start.jpg',
                'description' => 'Un tutorial paso a paso para principiantes.',
                'body' => 'Contenido paso a paso para aprender React...',
            ],
            [
                'title' => 'Los mejores editores de código en 2025',
                'slug' => 'mejores-editores-codigo-2025',
                'cover' => 'covers/code-editors.jpg',
                'description' => 'Comparativa de editores populares.',
                'body' => 'Contenido con comparaciones y ventajas...',

            ],
            [
                'title' => 'Introducción a Docker para desarrolladores',
                'slug' => 'introduccion-docker',
                'cover' => 'covers/docker-intro.jpg',
                'description' => 'Aprende qué es Docker y cómo usarlo.',
                'body' => 'Contenido completo de introducción a Docker...',

            ],
            [
                'title' => 'Errores comunes en PHP y cómo evitarlos',
                'slug' => 'errores-comunes-php',
                'cover' => 'covers/php-errors.jpg',
                'description' => 'Evita los errores más típicos en PHP.',
                'body' => 'Contenido con ejemplos de errores y soluciones...',

            ],
            [
                'title' => 'Las mejores prácticas en UX en 2025',
                'slug' => 'mejores-practicas-ux-2025',
                'cover' => 'covers/ux-best-practices.jpg',
                'description' => 'Consejos prácticos para mejorar la experiencia de usuario.',
                'body' => 'Contenido con buenas prácticas y ejemplos...',

            ],
            [
                'title' => 'Automatización con GitHub Actions',
                'slug' => 'automatizacion-github-actions',
                'cover' => 'covers/github-actions.jpg',
                'description' => 'Cómo automatizar procesos con GitHub Actions.',
                'body' => 'Contenido con ejemplos de workflows...',

            ],
            [
                'title' => '¿Qué es la arquitectura de microservicios?',
                'slug' => 'arquitectura-microservicios',
                'cover' => 'covers/microservices.jpg',
                'description' => 'Una explicación clara sobre microservicios.',
                'body' => 'Contenido explicando los conceptos principales...',
            ],
        ];

        $users = User::all();
        $categories = Category::all();

        foreach ($values as $value) {
            $userId = $users->random()->id;
            $post = Post::create([
                'title' => $value['title'],
                'date_publish' => Carbon::now(),
                'slug' => $value['slug'],
                'cover' => $value['cover'],
                'description' => $value['description'],
                'body' => $value['body'],
                'status' => Status::PUBLIC,
                'user_id' => $userId,
                'category_id' => $categories->where('user_id', $userId)->random()->id,
            ]);

            $tags = Tag::where('user_id', $userId)->get();

            $post->tags()->attach($tags->random(3));

        }
    }
}
