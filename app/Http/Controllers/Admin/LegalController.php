<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class LegalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.manage');
    }

    public function edit(): Response
    {
        return Inertia::render('Admin/Legal/Edit', [
            'legalNotice'   => Setting::get('legal_notice', ''),
            'privacyPolicy' => Setting::get('privacy_policy', ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'legal_notice'   => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
        ]);

        Setting::set('legal_notice', $request->input('legal_notice', ''));
        Setting::set('privacy_policy', $request->input('privacy_policy', ''));

        return Redirect::route('admin.legal.edit')->with('success', 'Pages légales mises à jour.');
    }
}