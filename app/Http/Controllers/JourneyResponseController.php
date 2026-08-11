<?php

namespace App\Http\Controllers;

use App\Models\JourneyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class JourneyResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save(Request $request, int $step): RedirectResponse
    {
        $request->validate([
            'data'      => ['required', 'array'],
            'completed' => ['boolean'],
        ]);

        $user = Auth::user();
        $application = $user->application;

        if (!$application || $application->status !== 'accepted') {
            return Redirect::route('dashboard')->with('error', 'Votre candidature doit être acceptée.');
        }

        if ($step > $application->journey_current_step) {
            return Redirect::route('dashboard')->with('error', "Cette étape n'est pas encore débloquée.");
        }

        JourneyResponse::updateOrCreate(
            ['user_id' => $user->id, 'step_number' => $step],
            [
                'data'           => $request->input('data'),
                'completed_at'   => $request->boolean('completed') ? now() : null,
                'rework_reason'  => $request->boolean('completed') ? null : null,
            ]
        );

        return Redirect::route('dashboard', ['tab' => 'parcours'])
            ->with('success', "Étape {$step} enregistrée.");
    }
}