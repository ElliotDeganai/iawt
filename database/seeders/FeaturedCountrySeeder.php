<?php

namespace Database\Seeders;

use App\Models\CountryPlace;
use App\Models\FeaturedCountry;
use App\Models\PlaceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeaturedCountrySeeder extends Seeder
{
    public function run(): void
    {
        // Charger les catégories par slug → id
        $catMap = PlaceCategory::pluck('id', 'slug')->all();

        $countries = [
            [
                'name' => "Côte d'Ivoire",
                'flag_emoji' => '🇨🇮',
                'flag_code' => 'ci',
                'tags' => 'Agriculture • Tourisme • Opportunités',
                'summary' => "Découvrez les opportunités, les secteurs porteurs et les acteurs clés du pays à l'honneur de ce mois-ci.",
                'content' => "La Côte d'Ivoire, premier producteur mondial de cacao, développe aussi un secteur touristique en plein essor autour de ses parcs nationaux et de ses plages. Les jeunes entrepreneurs y trouvent un terrain fertile pour l'agro-transformation et l'écotourisme.",
                'cover_image' => 'countries/cote-divoire-cover.jpg',
                'featured_month' => now()->startOfMonth(),
                'is_published' => true,
                'is_current' => true,
                'population' => '29,4 millions (2023)',
                'currency' => 'Franc CFA (XOF)',
                'timezone' => 'GMT (UTC+0)',
                'official_language' => 'Français',
                'sunrise_time' => '06:10',
                'sunset_time' => '18:15',
                'best_transport' => "Taxi compteur ou VTC à Abidjan, cars inter-villes (UTB, MTT) pour l'intérieur du pays",
                'highlights' => [
                    ['name' => 'Basilique Notre-Dame-de-la-Paix', 'description' => "À Yamoussoukro, la plus grande basilique du monde."],
                    ['name' => 'Grand-Bassam', 'description' => "Ancienne capitale coloniale classée à l'UNESCO."],
                    ['name' => 'Parc national de la Comoé', 'description' => "Réserve de biosphère UNESCO."],
                    ['name' => "Cathédrale Saint-Paul d'Abidjan", 'description' => "Architecture moderne surplombant la lagune Ébrié."],
                    ['name' => "Plages d'Assinie", 'description' => "Station balnéaire entre lagune et Atlantique."],
                ],
                'tourism_agencies' => [
                    ['name' => "Côte d'Ivoire Tourisme", 'website' => 'https://www.tourisme.gouv.ci', 'email' => 'contact@tourisme.gouv.ci', 'phone' => '+225 27 20 21 33 33'],
                    ['name' => 'Ivoire Voyages', 'website' => 'https://www.ivoirevoyages.com', 'email' => 'info@ivoirevoyages.com', 'phone' => '+225 27 22 44 00 00'],
                ],
                'places' => [
                    'lodging' => [
                        ['name' => 'Sofitel Abidjan Hôtel Ivoire', 'description' => 'Hôtel de référence en bord de lagune.'],
                        ['name' => 'La Maison Assinie', 'description' => 'Boutique-hôtel les pieds dans le sable.'],
                    ],
                    'shopping' => [
                        ['name' => 'Marché de Cocody', 'description' => 'Artisanat, tissus wax et pagnes traditionnels.'],
                        ['name' => 'Cap Sud', 'description' => "Centre commercial moderne d'Abidjan."],
                    ],
                    'food' => [
                        ['name' => 'Attiéké-poisson des maquis de Treichville', 'description' => "L'expérience culinaire locale incontournable."],
                        ['name' => "La Terrasse d'Abidjan", 'description' => 'Cuisine ivoirienne revisitée en bord de lagune.'],
                    ],
                    'nightlife' => [
                        ['name' => 'Rue Princesse (Yopougon)', 'description' => "Le cœur nocturne d'Abidjan, maquis et concerts live."],
                    ],
                    'activities' => [
                        ['name' => 'Surf à Grand-Bassam', 'description' => 'Spot de surf accessible aux débutants.'],
                        ['name' => 'Safari au parc de la Comoé', 'description' => 'Observation de la faune ouest-africaine.'],
                    ],
                    'learn_more' => [
                        ['name' => "Musée des Civilisations de Côte d'Ivoire", 'description' => 'Patrimoine culturel et artistique ivoirien à Abidjan.'],
                    ],
                ],
            ],
            [
                'name' => 'Sénégal',
                'flag_emoji' => '🇸🇳',
                'flag_code' => 'sn',
                'tags' => 'Pêche • Agriculture • Numérique',
                'summary' => "Un écosystème entrepreneurial dynamique porté par l'agro-industrie et le numérique.",
                'content' => "Le Sénégal combine une agriculture diversifiée et un secteur numérique en forte croissance, notamment autour de Dakar, devenue un hub tech régional reconnu.",
                'cover_image' => 'countries/senegal-cover.jpg',
                'featured_month' => now()->subMonth()->startOfMonth(),
                'is_published' => true,
                'is_current' => false,
                'population' => '18 millions (2023)',
                'currency' => 'Franc CFA (XOF)',
                'timezone' => 'GMT (UTC+0)',
                'official_language' => 'Français',
                'sunrise_time' => '07:05',
                'sunset_time' => '19:20',
                'best_transport' => "Taxi ou VTC à Dakar, cars rapides pour l'intérieur du pays",
                'highlights' => [
                    ['name' => 'Île de Gorée', 'description' => "Site mémoriel UNESCO lié à la traite négrière."],
                    ['name' => 'Lac Rose (Retba)', 'description' => 'Lac aux eaux roses, terminus du rallye Paris-Dakar.'],
                    ['name' => 'Saint-Louis', 'description' => "Ancienne capitale de l'AOF, classée à l'UNESCO."],
                    ['name' => 'Monument de la Renaissance africaine', 'description' => 'Statue monumentale surplombant Dakar.'],
                    ['name' => 'Parc national des oiseaux du Djoudj', 'description' => 'Troisième réserve ornithologique au monde.'],
                ],
                'tourism_agencies' => [
                    ['name' => 'Sénégal Tourisme (ASPT)', 'website' => 'https://www.senegal-tourisme.sn', 'email' => 'contact@aspt.sn', 'phone' => '+221 33 849 65 00'],
                    ['name' => 'Sahel Découverte', 'website' => 'https://www.saheldecouverte.com', 'email' => 'info@saheldecouverte.com', 'phone' => '+221 33 822 30 30'],
                ],
                'places' => [
                    'lodging' => [
                        ['name' => 'Radisson Blu Dakar Sea Plaza', 'description' => "Hôtel en bord d'océan au cœur de Dakar."],
                        ['name' => 'La Maison Rose (Lac Rose)', 'description' => 'Hébergement de charme au bord du lac.'],
                    ],
                    'shopping' => [
                        ['name' => 'Marché Sandaga', 'description' => 'Le grand marché populaire de Dakar.'],
                        ['name' => 'Marché Kermel', 'description' => 'Artisanat, épices et fleurs dans un cadre colonial rénové.'],
                    ],
                    'food' => [
                        ['name' => 'Thiéboudienne chez Le Djembé', 'description' => 'Le plat national sénégalais, riz au poisson.'],
                        ['name' => 'La Pointe des Almadies', 'description' => "Restaurants de fruits de mer face à l'Atlantique."],
                    ],
                    'nightlife' => [
                        ['name' => 'Quartier des Almadies', 'description' => 'Bars, clubs et musique live à Dakar.'],
                    ],
                    'activities' => [
                        ['name' => 'Surf aux Almadies', 'description' => "Spots réputés sur la pointe ouest de l'Afrique."],
                        ['name' => 'Excursion au Lac Rose', 'description' => 'Balade en 4x4 sur les dunes et récolte de sel.'],
                    ],
                    'learn_more' => [
                        ['name' => 'Musée des Civilisations Noires', 'description' => 'Grand musée panafricain inauguré en 2018.'],
                    ],
                ],
            ],
        ];

        foreach ($countries as $data) {
            // Extraire les lieux avant de créer le pays
            $places = $data['places'] ?? [];
            unset($data['places']);

            $slug = Str::slug($data['name']);
            $country = FeaturedCountry::updateOrCreate(['slug' => $slug], $data + ['slug' => $slug]);

            // Créer les lieux dans la table relationnelle
            $country->countryPlaces()->delete(); // reset si re-seed
            foreach ($places as $catSlug => $items) {
                $catId = $catMap[$catSlug] ?? null;
                if (!$catId) continue;

                foreach ($items as $pos => $item) {
                    CountryPlace::create([
                        'featured_country_id' => $country->id,
                        'place_category_id'   => $catId,
                        'name'                => $item['name'],
                        'description'          => $item['description'] ?? null,
                        'link'                => $item['link'] ?? null,
                        'position'            => $pos + 1,
                    ]);
                }
            }
        }
    }
}
