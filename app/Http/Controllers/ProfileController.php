<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $this->prefillFromApplication($user);

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Sync back to application
        $this->syncToApplication($user);

        return Redirect::route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);
        $user = $request->user();
        Auth::logout();
        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }

    private function prefillFromApplication($user): void
    {
        $app = Application::where('user_id', $user->id)->first();
        if (!$app) return;

        $updated = false;

        if (empty($user->country) && !empty($app->country_of_residence)) {
            $user->country = $app->country_of_residence;
            $updated = true;
        }
        if (empty($user->city) && !empty($app->city_of_residence)) {
            $user->city = $app->city_of_residence;
            $updated = true;
        }
        if (empty($user->whatsapp) && !empty($app->whatsapp_phone)) {
            $user->whatsapp = $app->whatsapp_phone;
            $updated = true;
        }
        if (empty($user->gender) && !empty($app->gender)) {
            $user->gender = $app->gender;
            $updated = true;
        }

        if ($updated) $user->save();
    }

    private function syncToApplication($user): void
    {
        $app = Application::where('user_id', $user->id)->first();
        if (!$app) return;

        $updated = false;

        if (!empty($user->country) && $app->country_of_residence !== $user->country) {
            $app->country_of_residence = $user->country;
            $updated = true;
        }
        if (!empty($user->city) && $app->city_of_residence !== $user->city) {
            $app->city_of_residence = $user->city;
            $updated = true;
        }
        if (!empty($user->whatsapp) && $app->whatsapp_phone !== $user->whatsapp) {
            $app->whatsapp_phone = $user->whatsapp;
            $updated = true;
        }
        if (!empty($user->gender) && $app->gender !== $user->gender) {
            $app->gender = $user->gender;
            $updated = true;
        }

        if ($updated) $app->save();
    }
}
