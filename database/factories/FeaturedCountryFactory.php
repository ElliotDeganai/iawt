<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FeaturedCountryFactory extends Factory
{
    protected $model = \App\Models\FeaturedCountry::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            "Côte d'Ivoire", 'Sénégal', 'Cameroun', 'Bénin', 'Togo', 'Kenya', 'Rwanda', 'Maroc',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'flag_emoji' => '🌍',
            'tags' => 'Agriculture • Tourisme • Opportunités',
            'summary' => "Découvrez les opportunités, les secteurs porteurs et les acteurs clés du pays à l'honneur de ce mois-ci.",
            'content' => fake()->paragraphs(4, true),
            'featured_month' => now()->subMonths(fake()->numberBetween(0, 5))->startOfMonth(),
            'is_published' => true,
        ];
    }
}