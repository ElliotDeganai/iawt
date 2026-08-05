<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HomeContentController extends Controller
{
    protected array $textKeys = ['hero_title', 'hero_subtitle', 'program_title', 'program_text'];

    public function __construct()
    {
        $this->middleware('permission:home_content.manage');
    }

    public function edit(): Response
    {
        $settings = collect($this->textKeys)->mapWithKeys(fn ($key) => [$key => Setting::get($key, '')]);

        return Inertia::render('Admin/HomeContent/Edit', [
            'settings' => $settings,
            'heroImage' => Setting::get('hero_image'),
            'programImage' => Setting::get('program_image'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:191'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'program_title' => ['nullable', 'string', 'max:191'],
            'program_text' => ['nullable', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'program_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
        ]);

        foreach (array_intersect_key($validated, array_flip($this->textKeys)) as $key => $value) {
            Setting::set($key, $value);
        }

        foreach (['hero_image', 'program_image'] as $imageKey) {
            if ($request->hasFile($imageKey)) {
                $current = Setting::get($imageKey);
                if ($current) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $current));
                }
                $path = $request->file($imageKey)->store('home', 'public');
                Setting::set($imageKey, "/storage/{$path}");
            }
        }

        return Redirect::route('admin.home-content.edit')->with('success', 'Contenu de la page d\'accueil mis à jour avec succès.');
    }
}
