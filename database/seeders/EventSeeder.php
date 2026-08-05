<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['title' => 'Webinaire agriculture durable', 'description' => 'Introduction aux pratiques agricoles durables pour les jeunes entrepreneurs africains.', 'date' => now()->startOfMonth()->addDays(5), 'time' => '10:00', 'is_featured' => false],
            ['title' => 'Forum tourisme responsable', 'description' => 'Rencontres et échanges autour du tourisme durable en Afrique de l\'Ouest.', 'date' => now()->startOfMonth()->addDays(6), 'time' => '14:00', 'is_featured' => true],
            ['title' => 'Pitch session promotion 2026', 'description' => 'Présentation des projets de la promotion en cours devant un jury de mentors.', 'date' => now()->startOfMonth()->addDays(6), 'time' => '18:00', 'is_featured' => false],
            ['title' => 'Atelier structuration financière', 'description' => 'Comment construire un plan financier solide et chercher des financements.', 'date' => now()->startOfMonth()->addDays(11), 'time' => '09:00', 'is_featured' => false],
            ['title' => 'Session mentorat entrepreneurs', 'description' => 'Rendez-vous individuel avec les mentors du réseau InAfrikaWeTrust.', 'date' => now()->startOfMonth()->addDays(13), 'time' => '10:00', 'is_featured' => false],
            ['title' => 'Demo Day — Promotion 2026', 'description' => 'Venez découvrir les projets de la promotion 2026 lors de notre Demo Day annuel. Pitchs en live, rencontres avec les mentors et investisseurs.', 'date' => now()->startOfMonth()->addDays(13), 'time' => '15:00', 'is_featured' => true, 'url' => 'https://inafrikawetrust.org/demo-day'],
            ['title' => 'Table ronde agriculture', 'description' => 'Débat autour des enjeux de la filière agroalimentaire en Afrique subsaharienne.', 'date' => now()->startOfMonth()->addDays(19), 'time' => '18:30', 'is_featured' => false],
            ['title' => 'Rencontre partenaires institutionnels', 'description' => 'Réunion avec les partenaires financiers et institutionnels du programme.', 'date' => now()->startOfMonth()->addDays(21), 'time' => '09:30', 'is_featured' => true],
        ];

        foreach ($events as $data) {
            Event::create($data);
        }
    }
}
