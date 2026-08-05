<?php

namespace Database\Seeders;

use App\Models\PlaceCategory;
use Illuminate\Database\Seeder;

class PlaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Où se loger',           'slug' => 'lodging',    'icon' => 'lodging',    'position' => 1],
            ['name' => 'Où faire du shopping',  'slug' => 'shopping',   'icon' => 'shopping',   'position' => 2],
            ['name' => 'Où manger',             'slug' => 'food',       'icon' => 'food',       'position' => 3],
            ['name' => "Où s'enjailler",        'slug' => 'nightlife',  'icon' => 'nightlife',  'position' => 4],
            ['name' => 'Où se dépenser',        'slug' => 'activities', 'icon' => 'activities', 'position' => 5],
            ['name' => 'Où en apprendre plus',  'slug' => 'learn_more', 'icon' => 'learn_more', 'position' => 6],
        ];

        foreach ($categories as $cat) {
            PlaceCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
