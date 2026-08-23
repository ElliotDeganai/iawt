<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EventCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/EventCategories/Index', [
            'categories' => EventCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        EventCategory::create([
            'name'       => $data['name'],
            'slug'       => Str::slug($data['name']),
            'color'      => $data['color'],
            'sort_order' => EventCategory::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.event-categories.index')->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, EventCategory $eventCategory)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $eventCategory->update([
            'name'  => $data['name'],
            'slug'  => Str::slug($data['name']),
            'color' => $data['color'],
        ]);

        return redirect()->route('admin.event-categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(EventCategory $eventCategory)
    {
        $eventCategory->delete();
        return redirect()->route('admin.event-categories.index')->with('success', 'Catégorie supprimée.');
    }
}
