<?php

namespace App\Http\Controllers;

use App\Models\Engagement;
use App\Models\FeaturedCountry;
use App\Models\JourneyStep;
use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil.
     *
     * Contenu piloté depuis /admin : hero + section "programme" (Setting),
     * "pays à l'honneur" (FeaturedCountry), "nos engagements" (Engagement)
     * et "parcours en 7 étapes" (JourneyStep).
     * Les blocs "statistiques", "partenaires" et "témoignage" restent des
     * données fixes ci-dessous ; ils deviendront pilotés par la base dans
     * un prochain bloc.
     */
    public function index(): Response
    {
        $featuredCountry = FeaturedCountry::current();

        return Inertia::render('Home', [
            'settings' => [
                'site_name' => Setting::get('site_name', 'InAfrikaWeTrust'),
                'tagline' => Setting::get('tagline', "Entreprendre l'Afrique de demain"),
                'description' => Setting::get('description', ''),
                'hero_title' => Setting::get('hero_title', "Entreprendre l'Afrique de demain"),
                'hero_subtitle' => Setting::get('hero_subtitle', "Un programme d'accompagnement pour les jeunes entrepreneurs africains dans les secteurs du tourisme et de l'agriculture."),
                'hero_image' => Setting::get('hero_image'),
                'program_title' => Setting::get('program_title', 'Un accompagnement complet pour transformer votre idée en entreprise durable.'),
                'program_text' => Setting::get('program_text'),
                'program_image' => Setting::get('program_image'),
            ],

            'engagements' => Engagement::orderBy('position')->get(['icon', 'title', 'description']),

            'journeySteps' => JourneyStep::orderBy('position')->get(['icon', 'label']),

            'featuredCountry' => $featuredCountry ? [
                'slug' => $featuredCountry->slug,
                'name' => $featuredCountry->name,
                'flag' => $featuredCountry->flag_emoji,
                'flag_code' => $featuredCountry->flag_code,
                'flag_image' => $featuredCountry->flag_image ? "/storage/{$featuredCountry->flag_image}" : null,
                'tags' => $featuredCountry->tags,
                'description' => $featuredCountry->summary,
                'cover_image' => $featuredCountry->cover_url,
            ] : null,

            'stats' => \App\Models\Stat::orderBy('position')->get()->map(fn ($s) => [
                'value' => $s->value,
                'label' => $s->label,
            ]),

            'partners' => \App\Models\Partner::orderBy('position')->get()->map(fn ($p) => [
                'name' => $p->name,
                'logo' => $p->logo ? "/storage/{$p->logo}" : null,
                'url'  => $p->url,
            ]),

            'testimonials' => \App\Models\Testimonial::approved()
                ->orderBy('position')
                ->get()
                ->map(fn ($t) => [
                    'quote'  => $t->quote,
                    'name'   => $t->author_name,
                    'meta'   => $t->author_meta,
                    'photo'  => $t->author_photo ? "/storage/{$t->author_photo}" : null,
                ]),
        ]);
    }
}
