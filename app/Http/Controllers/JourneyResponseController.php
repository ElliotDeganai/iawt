<?php

namespace App\Http\Controllers;

use App\Models\JourneyResponse;
use App\Models\JourneyStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\StepSubmitted;
use App\Notifications\StepSubmittedForAdmin;
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
                'validated_at'   => $request->boolean('completed') ? null : null,
                'rework_reason'  => null,
            ]
        );

        // Send notifications on completed step (first submission or resubmission)
        if ($request->boolean('completed')) {
            $user->notify(new StepSubmitted($step));

            $application->load('user');
            User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))
                ->get()
                ->each(fn ($admin) => $admin->notify(new StepSubmittedForAdmin($application, $step)));
        }

        // Sync profile fields from journey data
        $this->syncProfileFromJourney($user, $step, $request->input('data'));

        $totalSteps = JourneyStep::count();

        if ($request->boolean('completed')) {
            if ($step < $totalSteps) {
                $message = "Étape {$step} soumise avec succès. Votre accompagnateur va l'examiner — une fois validée, l'étape suivante sera débloquée.";
            } else {
                $message = "Dernière étape soumise avec succès. Votre accompagnateur va l'examiner pour finaliser votre parcours. Félicitations pour être arrivé(e) jusqu'ici !";
            }
        } else {
            $message = "Brouillon de l'étape {$step} enregistré.";
        }

        return Redirect::route('dashboard', ['tab' => 'parcours'])
            ->with('success', $message);
    }

    private function syncProfileFromJourney($user, int $step, array $data): void
    {
        $updated = false;

        if ($step === 2) {
            if (!empty($data['zone_country']) && empty($user->country)) {
                $user->country = $data['zone_country'];
                $updated = true;
            }
            if (!empty($data['zone_city']) && empty($user->city)) {
                $user->city = $data['zone_city'];
                $updated = true;
            }
        }

        if ($updated) {
            $user->save();
        }
    }
}