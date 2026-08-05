<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'author_name' => 'Aïcha K.',
                'author_meta' => 'Promotion 2024 · AgriLink CI',
                'quote'       => "Le programme m'a permis de structurer mon projet agricole et d'obtenir mes premiers clients.",
                'position'    => 1,
            ],
            [
                'author_name' => 'Mamadou D.',
                'author_meta' => 'Promotion 2024 · TourAfrik',
                'quote'       => "Grâce à InAfrikaWeTrust, j'ai pu transformer une simple idée en un business model solide avec des partenaires concrets.",
                'position'    => 2,
            ],
            [
                'author_name' => 'Fatou T.',
                'author_meta' => 'Promotion 2023 · GreenHarvest',
                'quote'       => "L'accompagnement juridique et financier m'a fait gagner un temps précieux. Je recommande ce programme à tous les jeunes entrepreneurs.",
                'position'    => 3,
            ],
        ];

        foreach ($items as $item) {
            Testimonial::firstOrCreate(['author_name' => $item['author_name']], $item);
        }
    }
}
