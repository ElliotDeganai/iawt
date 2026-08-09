<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CountryMedia;
use App\Models\FeaturedCountry;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class CountryMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:countries.manage');
    }

    /**
     * Upload une ou plusieurs photos.
     */
    public function store(Request $request, FeaturedCountry $country): RedirectResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'max:20'],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif'],
        ]);

        $position = $country->media()->max('position') ?? 0;

        foreach ($request->file('files') as $file) {
            $position++;
            $path = ImageService::store($file, "countries/{$country->slug}/gallery");
            $country->media()->create([
                'type' => 'photo',
                'path' => $path,
                'position' => $position,
            ]);
        }

        return Redirect::route('admin.countries.edit', $country->slug)
            ->with('success', 'Photos ajoutées avec succès.');
    }

    /**
     * Ajoute une vidéo (URL YouTube / Vimeo).
     */
    public function storeVideo(Request $request, FeaturedCountry $country): RedirectResponse
    {
        $request->validate([
            'video_url' => ['required', 'url', 'max:500'],
            'caption'   => ['nullable', 'string', 'max:191'],
        ]);

        $position = $country->media()->max('position') ?? 0;

        $country->media()->create([
            'type'      => 'video',
            'video_url' => $request->video_url,
            'caption'   => $request->caption,
            'position'  => $position + 1,
        ]);

        return Redirect::route('admin.countries.edit', $country->slug)
            ->with('success', 'Vidéo ajoutée avec succès.');
    }

    /**
     * Supprime un item de la galerie.
     */
    public function destroy(FeaturedCountry $country, CountryMedia $media): RedirectResponse
    {
        if ($media->featured_country_id !== $country->id) {
            abort(403);
        }

        // Si cet item était la cover, on la retire
        if ($country->cover_media_id === $media->id) {
            $country->update(['cover_media_id' => null]);
        }

        if ($media->type === 'photo' && $media->path) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return Redirect::route('admin.countries.edit', $country->slug)
            ->with('success', 'Média supprimé.');
    }

    /**
     * Définit cet item comme photo de couverture.
     */
    public function setCover(FeaturedCountry $country, CountryMedia $media): RedirectResponse
    {
        if ($media->featured_country_id !== $country->id || $media->type !== 'photo') {
            abort(403);
        }

        $country->update(['cover_media_id' => $media->id]);

        return Redirect::route('admin.countries.edit', $country->slug)
            ->with('success', 'Photo de couverture mise à jour.');
    }

    /**
     * Met à jour l'ordre des médias (drag & drop → POST JSON).
     */
    public function reorder(Request $request, FeaturedCountry $country): JsonResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $pos => $id) {
            CountryMedia::where('id', $id)
                ->where('featured_country_id', $country->id)
                ->update(['position' => $pos + 1]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Met à jour la légende d'un item.
     */
    public function updateCaption(Request $request, FeaturedCountry $country, CountryMedia $media): RedirectResponse
    {
        if ($media->featured_country_id !== $country->id) {
            abort(403);
        }

        $request->validate(['caption' => ['nullable', 'string', 'max:191']]);
        $media->update(['caption' => $request->caption]);

        return Redirect::route('admin.countries.edit', $country->slug)
            ->with('success', 'Légende mise à jour.');
    }
}
