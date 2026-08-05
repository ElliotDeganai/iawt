<?php

namespace App\Http\Controllers;

use App\Models\FeaturedCountry;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    public function index(): Response
    {
        // On charge tous les pays publiés qui ont au moins un média,
        // avec leurs médias triés par position.
        $countries = FeaturedCountry::where('is_published', true)
            ->with(['media' => fn ($q) => $q->orderBy('position')])
            ->get()
            ->filter(fn ($c) => $c->media->isNotEmpty())
            ->map(fn ($c) => [
                'id'        => $c->id,
                'name'      => $c->name,
                'slug'      => $c->slug,
                'flag_code' => $c->flag_code,
                'flag_emoji'=> $c->flag_emoji,
                'tags'      => $c->tags,
                'media'     => $c->media->map(fn ($m) => [
                    'id'      => $m->id,
                    'type'    => $m->type,
                    'url'     => $m->url,
                    'caption' => $m->caption,
                ]),
            ])
            ->values();

        return Inertia::render('Gallery/Index', [
            'countries' => $countries,
        ]);
    }
}
