<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
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
                'date_publish' => '2025-04-01',
                'slug' => 'futuro-inteligencia-artificial',
                'cover' => 'covers/ai-future.jpg',
                'description' => 'Una mirada hacia lo que nos espera con la IA.',
                'body' => 'Contenido completo sobre el futuro de la IA...',
            ],
            [
                'title' => 'Guía completa de Laravel 11',
                'date_publish' => '2025-03-25',
                'slug' => 'guia-laravel-11',
                'cover' => 'covers/laravel-11.jpg',
                'description' => 'Todo lo que necesitas saber sobre Laravel 11.',
                'body' => 'Contenido de la guía completa de Laravel 11...',
            ],
            [
                'title' => 'Tendencias en diseño web 2025',
                'date_publish' => '2025-04-05',
                'slug' => 'tendencias-diseno-web-2025',
                'cover' => 'covers/design-trends.jpg',
                'description' => 'Descubre lo más actual en diseño web.',
                'body' => 'Contenido detallado de las tendencias...',
            ],
            [
                'title' => 'Cómo comenzar con React desde cero',
                'date_publish' => '2025-04-02',
                'slug' => 'comenzar-react-desde-cero',
                'cover' => 'covers/react-start.jpg',
                'description' => 'Un tutorial paso a paso para principiantes.',
                'body' => 'Contenido paso a paso para aprender React...',
            ],
            [
                'title' => 'Los mejores editores de código en 2025',
                'date_publish' => '2025-03-28',
                'slug' => 'mejores-editores-codigo-2025',
                'cover' => 'covers/code-editors.jpg',
                'description' => 'Comparativa de editores populares.',
                'body' => 'Contenido con comparaciones y ventajas...',

            ],
            [
                'title' => 'Introducción a Docker para desarrolladores',
                'date_publish' => '2025-04-06',
                'slug' => 'introduccion-docker',
                'cover' => 'covers/docker-intro.jpg',
                'description' => 'Aprende qué es Docker y cómo usarlo.',
                'body' => 'Contenido completo de introducción a Docker...',

            ],
            [
                'title' => 'Errores comunes en PHP y cómo evitarlos',
                'date_publish' => '2025-04-04',
                'slug' => 'errores-comunes-php',
                'cover' => 'covers/php-errors.jpg',
                'description' => 'Evita los errores más típicos en PHP.',
                'body' => 'Contenido con ejemplos de errores y soluciones...',

            ],
            [
                'title' => 'Las mejores prácticas en UX en 2025',
                'date_publish' => '2025-03-30',
                'slug' => 'mejores-practicas-ux-2025',
                'cover' => 'covers/ux-best-practices.jpg',
                'description' => 'Consejos prácticos para mejorar la experiencia de usuario.',
                'body' => 'Contenido con buenas prácticas y ejemplos...',

            ],
            [
                'title' => 'Automatización con GitHub Actions',
                'date_publish' => '2025-04-07',
                'slug' => 'automatizacion-github-actions',
                'cover' => 'covers/github-actions.jpg',
                'description' => 'Cómo automatizar procesos con GitHub Actions.',
                'body' => 'Contenido con ejemplos de workflows...',

            ],
            [
                'title' => '¿Qué es la arquitectura de microservicios?',
                'date_publish' => '2025-04-03',
                'slug' => 'arquitectura-microservicios',
                'cover' => 'covers/microservices.jpg',
                'description' => 'Una explicación clara sobre microservicios.',
                'body' => 'Contenido explicando los conceptos principales...',
            ],
        ];

        $users = User::all();
        $categories = Category::all();

        foreach ($values as $value) {
            \App\Models\Post::create([
                'title' => $value['title'],
                'date_publish' => $value['date_publish'],
                'slug' => $value['slug'],
                'cover' => $value['cover'],
                'description' => $value['description'],
                'body' => $value['body'],
                'status' => \App\Enums\Status::PUBLIC,
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}
