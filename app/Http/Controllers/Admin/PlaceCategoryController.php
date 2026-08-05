<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PlaceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:countries.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/PlaceCategories/Index', [
            'categories' => PlaceCategory::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:191'],
            'icon'     => ['required', 'string', 'max:50'],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        PlaceCategory::create($data);

        return Redirect::route('admin.place-categories.index')->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, PlaceCategory $placeCategory): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:191'],
            'icon'     => ['required', 'string', 'max:50'],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $placeCategory->update($data);

        return Redirect::route('admin.place-categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(PlaceCategory $placeCategory): RedirectResponse
    {
        $placeCategory->delete();

        return Redirect::route('admin.place-categories.index')->with('success', 'Catégorie supprimée.');
    }
}
