<?php

namespace App\Http\Controllers;

use App\Models\FeaturedCountry;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    /**
     * Affiche directement la fiche du pays à l'honneur actuellement sélectionné
     * (c'est vers cette action que pointe le lien "Le pays à l'honneur" du menu).
     */
    public function current(): Response
    {
        $country = FeaturedCountry::current();
        abort_unless($country && $country->is_published, 404);

        return Inertia::render('Countries/Show', [
            'country' => $country->load('coverMedia', 'media', 'countryPlaces.category'),
        ]);
    }

    public function index(): Response
    {
        return Inertia::render('Countries/Index', [
            'countries' => FeaturedCountry::where('is_published', true)
                ->with('coverMedia')
                ->orderByDesc('featured_month')
                ->get(),
        ]);
    }

    public function show(FeaturedCountry $country): Response
    {
        abort_unless($country->is_published, 404);

        return Inertia::render('Countries/Show', [
            'country' => $country->load('coverMedia', 'media', 'countryPlaces.category'),
        ]);
    }
}
