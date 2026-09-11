<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ResourceCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/ResourceCategories/Index', [
            'categories' => ResourceCategory::orderBy('sort_order')->withCount('resources')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'integer'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? ResourceCategory::max('sort_order') + 1;

        ResourceCategory::create($data);
        return Redirect::route('admin.resource-categories.index')->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, ResourceCategory $resourceCategory): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'max:50'],
            'sort_order'  => ['nullable', 'integer'],
        ]);
        $data['slug'] = Str::slug($data['name']);

        $resourceCategory->update($data);
        return Redirect::route('admin.resource-categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(ResourceCategory $resourceCategory): RedirectResponse
    {
        if ($resourceCategory->resources()->count()) {
            return Redirect::route('admin.resource-categories.index')->with('error', 'Impossible de supprimer une catégorie qui contient des ressources.');
        }
        $resourceCategory->delete();
        return Redirect::route('admin.resource-categories.index')->with('success', 'Catégorie supprimée.');
    }
}
