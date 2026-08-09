<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFeaturedCountryRequest;
use App\Http\Requests\Admin\UpdateFeaturedCountryRequest;
use App\Models\FeaturedCountry;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class FeaturedCountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:countries.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Countries/Index', [
            'countries' => FeaturedCountry::orderByDesc('featured_month')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Countries/Create', [
            'placeCategories' => FeaturedCountry::placeCategories(),
        ]);
    }

    public function store(StoreFeaturedCountryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = ImageService::store($request->file('cover_image'), 'countries');
        }

        if ($request->hasFile('flag_image')) {
            $data['flag_image'] = ImageService::store($request->file('flag_image'), 'countries/flags', 800, 800);
        }

        if ($request->hasFile('map_image')) {
            $data['map_image'] = ImageService::store($request->file('map_image'), 'countries/maps');
        }

        $country = FeaturedCountry::create($data);

        // Sauvegarder les lieux
        $this->syncPlaces($request, $country);

        return Redirect::route('admin.countries.index')->with('success', 'Pays à l\'honneur créé avec succès.');
    }

    public function edit(FeaturedCountry $country): Response
    {
        return Inertia::render('Admin/Countries/Edit', [
            'country'         => $country->load('media', 'countryPlaces.category'),
            'placeCategories' => FeaturedCountry::placeCategories(),
        ]);
    }

    public function update(UpdateFeaturedCountryRequest $request, FeaturedCountry $country): RedirectResponse
    {
        $data = $request->validated();

        // Cover image
        if ($request->hasFile('cover_image')) {
            if ($country->cover_image) {
                Storage::disk('public')->delete($country->cover_image);
            }
            $data['cover_image'] = ImageService::store($request->file('cover_image'), 'countries');
        } else {
            unset($data['cover_image']);
        }

        // Flag image
        if ($request->hasFile('flag_image')) {
            if ($country->flag_image) {
                Storage::disk('public')->delete($country->flag_image);
            }
            $data['flag_image'] = ImageService::store($request->file('flag_image'), 'countries/flags', 800, 800);
        } else {
            unset($data['flag_image']);
        }

        // Map image — suppression explicite ou remplacement
        if ($request->boolean('remove_map_image')) {
            if ($country->map_image) {
                Storage::disk('public')->delete($country->map_image);
            }
            $data['map_image'] = null;
        } elseif ($request->hasFile('map_image')) {
            if ($country->map_image) {
                Storage::disk('public')->delete($country->map_image);
            }
            $data['map_image'] = ImageService::store($request->file('map_image'), 'countries/maps');
        } else {
            unset($data['map_image']);
        }

        unset($data['remove_map_image']);

        $country->update($data);

        // Sauvegarder les lieux
        $this->syncPlaces($request, $country);

        return Redirect::route('admin.countries.index')->with('success', 'Pays à l\'honneur mis à jour avec succès.');
    }

    /**
     * Synchronise les lieux d'un pays à partir du formulaire.
     * Format attendu : country_places[category_id][] = {name, description, link}
     */
    private function syncPlaces(Request $request, FeaturedCountry $country): void
    {
        $incoming = $request->input('country_places', []);

        // Supprimer les anciens
        $country->countryPlaces()->delete();

        foreach ($incoming as $categoryId => $items) {
            if (!is_array($items)) continue;
            foreach ($items as $pos => $item) {
                if (empty($item['name'])) continue;
                $country->countryPlaces()->create([
                    'place_category_id' => $categoryId,
                    'name'              => $item['name'],
                    'description'       => $item['description'] ?? null,
                    'link'              => $item['link'] ?? null,
                    'position'          => $pos + 1,
                ]);
            }
        }
    }

    public function destroy(FeaturedCountry $country): RedirectResponse
    {
        if ($country->cover_image) {
            Storage::disk('public')->delete($country->cover_image);
        }

        if ($country->flag_image) {
            Storage::disk('public')->delete($country->flag_image);
        }

        if ($country->map_image) {
            Storage::disk('public')->delete($country->map_image);
        }

        $country->delete();

        return Redirect::route('admin.countries.index')->with('success', 'Pays à l\'honneur supprimé avec succès.');
    }

    /**
     * Sélectionne ce pays comme LE pays à l'honneur actuel (sélection unique et exclusive).
     */
    public function setCurrent(FeaturedCountry $country): RedirectResponse
    {
        DB::transaction(function () use ($country) {
            FeaturedCountry::where('id', '!=', $country->id)->update(['is_current' => false]);
            $country->update(['is_current' => true, 'is_published' => true]);
        });

        return Redirect::route('admin.countries.index')->with('success', "« {$country->name} » est maintenant le pays à l'honneur.");
    }
}