<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home_content.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Partners/Index', [
            'partners' => Partner::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:191'],
            'logo'     => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg,webp'],
            'url'      => ['nullable', 'url', 'max:500'],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = ImageService::store($request->file('logo'), 'partners', 600, 600);
        }

        Partner::create($data);

        return Redirect::route('admin.partners.index')->with('success', 'Partenaire ajouté.');
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:191'],
            'logo'         => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg,webp'],
            'remove_logo'  => ['boolean'],
            'url'          => ['nullable', 'url', 'max:500'],
            'position'     => ['required', 'integer', 'min:0'],
        ]);

        if ($request->boolean('remove_logo')) {
            if ($partner->logo) Storage::disk('public')->delete($partner->logo);
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($partner->logo) Storage::disk('public')->delete($partner->logo);
            $data['logo'] = ImageService::store($request->file('logo'), 'partners', 600, 600);
        } else {
            unset($data['logo']);
        }

        unset($data['remove_logo']);
        $partner->update($data);

        return Redirect::route('admin.partners.index')->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        if ($partner->logo) Storage::disk('public')->delete($partner->logo);
        $partner->delete();
        return Redirect::route('admin.partners.index')->with('success', 'Partenaire supprimé.');
    }
}