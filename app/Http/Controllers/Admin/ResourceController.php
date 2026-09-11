<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ResourceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Resources/Index', [
            'categories' => ResourceCategory::orderBy('sort_order')->withCount('resources')->get(),
            'resources'  => Resource::with('category')->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Resources/Edit', [
            'resource'   => null,
            'categories' => ResourceCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id'  => ['required', 'exists:resource_categories,id'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'type'         => ['required', 'in:pdf,link,video,template'],
            'url'          => ['nullable', 'url'],
            'file'         => ['nullable', 'file', 'max:20480'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('resources', 'public');
        }
        unset($data['file']);

        Resource::create($data);
        return Redirect::route('admin.resources.index')->with('success', 'Ressource créée.');
    }

    public function edit(Resource $resource): Response
    {
        return Inertia::render('Admin/Resources/Edit', [
            'resource'   => $resource,
            'categories' => ResourceCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $data = $request->validate([
            'category_id'  => ['required', 'exists:resource_categories,id'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'type'         => ['required', 'in:pdf,link,video,template'],
            'url'          => ['nullable', 'url'],
            'file'         => ['nullable', 'file', 'max:20480'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('file')) {
            if ($resource->file_path) Storage::disk('public')->delete($resource->file_path);
            $data['file_path'] = $request->file('file')->store('resources', 'public');
        }
        unset($data['file']);

        $resource->update($data);
        return Redirect::route('admin.resources.index')->with('success', 'Ressource mise à jour.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        if ($resource->file_path) Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        return Redirect::route('admin.resources.index')->with('success', 'Ressource supprimée.');
    }
}
