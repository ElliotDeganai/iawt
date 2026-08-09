<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;

class LegalPageController extends Controller
{
    public function legalNotice(): Response
    {
        return Inertia::render('Legal/LegalNotice', [
            'content' => Setting::get('legal_notice', ''),
        ]);
    }

    public function privacyPolicy(): Response
    {
        return Inertia::render('Legal/PrivacyPolicy', [
            'content' => Setting::get('privacy_policy', ''),
        ]);
    }
}