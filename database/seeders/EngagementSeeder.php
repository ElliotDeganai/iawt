<?php

namespace Database\Seeders;

use App\Models\Engagement;
use Illuminate\Database\Seeder;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['icon' => 'leaf', 'title' => 'Agriculture durable', 'description' => 'Promouvoir des chaînes de valeur agricoles compétitives et durables pour nourrir l\'Afrique.', 'position' => 1],
            ['icon' => 'palm', 'title' => 'Tourisme responsable', 'description' => 'Valoriser les territoires, les cultures et les patrimoines pour un tourisme durable et inclusif.', 'position' => 2],
            ['icon' => 'rocket', 'title' => 'Entrepreneuriat', 'description' => 'Créer des entreprises pérennes, génératrices d\'emplois et de valeur pour les communautés.', 'position' => 3],
        ];

        foreach ($items as $item) {
            Engagement::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}
