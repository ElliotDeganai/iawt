<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Salons professionnels',              'color' => '#1E40AF'],
            ['name' => 'Concerts',                           'color' => '#9333EA'],
            ['name' => 'Marchés et foires',                  'color' => '#059669'],
            ['name' => 'Concours et compétitions',           'color' => '#DC2626'],
            ['name' => 'Festivals de musique',               'color' => '#D946EF'],
            ['name' => 'Événements sportifs',                'color' => '#EA580C'],
            ['name' => 'Festivals culturels et traditionnels','color' => '#CA8A04'],
            ['name' => 'Fêtes et cérémonies traditionnelles','color' => '#0891B2'],
            ['name' => 'Journées internationales',           'color' => '#4F46E5'],
            ['name' => 'Conférences et forums',              'color' => '#0D9488'],
            ['name' => 'Fête nationale',                     'color' => '#BE123C'],
        ];

        foreach ($categories as $i => $cat) {
            EventCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['slug' => Str::slug($cat['name']), 'sort_order' => $i])
            );
        }
    }
}
