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
        $settings['logo'] = Setting::get('logo', '/images/logo-default.svg');

        return Inertia::render('Admin/Settings/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name'     => ['required', 'string', 'max:191'],
            'tagline'       => ['nullable', 'string', 'max:191'],
            'contact_email' => ['required', 'email', 'max:191'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'logo'          => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp'],
        ]);

        // Traitement du logo
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo && $oldLogo !== '/images/logo-default.svg') {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldLogo));
            }

            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', '/storage/' . $path);
        }

        unset($validated['logo']);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return Redirect::route('admin.settings.edit')->with('success', 'Réglages mis à jour avec succès.');
    }
}