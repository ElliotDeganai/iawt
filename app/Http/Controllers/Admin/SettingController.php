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

class SettingController extends Controller
{
    protected array $keys = ['site_name', 'tagline', 'contact_email', 'description'];

    public function __construct()
    {
        $this->middleware('permission:settings.manage');
    }

    public function edit(): Response
    {
        $settings = collect($this->keys)->mapWithKeys(fn ($key) => [$key => Setting::get($key, '')]);

        return Inertia::render('Admin/Settings/Edit', [
            'settings' => $settings,
            'logo' => Setting::get('logo', '/images/logo-default.svg'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        //dd($request);
        //dd($request->all(), $request->hasFile('logo'), ini_get('post_max_size'), ini_get('upload_max_filesize'));
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:191'],
            'tagline' => ['nullable', 'string', 'max:191'],
            'contact_email' => ['required', 'email', 'max:191'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:1024'],
        ]);

        foreach (array_intersect_key($validated, array_flip($this->keys)) as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('logo')) {
            $current = Setting::get('logo');
            if ($current && str_starts_with($current, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $current));
            }

            $path = $request->file('logo')->store('branding', 'public');
            Setting::set('logo', "/storage/{$path}");
        }

        return Redirect::route('admin.settings.edit')->with('success', 'Réglages mis à jour avec succès.');
    }
}