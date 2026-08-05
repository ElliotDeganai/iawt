<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    // ──────────────────────────────────────────────
    // Page d'accueil candidature (publique + gate)
    // ──────────────────────────────────────────────
    public function index(): Response
    {
        $application = null;

        if (Auth::check()) {
            $application = Application::where('user_id', Auth::id())->first();
        }

        return Inertia::render('Application/Index', [
            'application' => $application ? [
                'current_step' => $application->current_step,
                'status'       => $application->status,
            ] : null,
        ]);
    }

    // ──────────────────────────────────────────────
    // Wizard : affiche l'étape courante
    // ──────────────────────────────────────────────
    public function show(): Response
    {
        $application = $this->getOrCreate();

        if ($application->isSubmitted()) {
            return Inertia::render('Application/Submitted', [
                'application' => $application,
            ]);
        }

        return Inertia::render('Application/Wizard', [
            'application' => $application,
            'step'        => $application->current_step,
        ]);
    }

    // ──────────────────────────────────────────────
    // Sauvegarde d'une étape (PATCH)
    // ──────────────────────────────────────────────
    public function update(Request $request): RedirectResponse
    {
        $application = $this->getOrCreate();

        if ($application->isSubmitted()) {
            return Redirect::route('application.show');
        }

        $step = (int) $request->input('step', $application->current_step);

        $data = match ($step) {
            1 => $this->validateStep1($request),
            2 => $this->validateStep2($request),
            3 => $this->validateStep3($request, $application),
            4 => $this->validateStep4($request),
            default => [],
        };

        // Avancer à l'étape suivante si on valide l'étape courante
        $nextStep = min($step + 1, 4);
        if ($application->current_step <= $step) {
            $data['current_step'] = $nextStep;
        }

        $application->update($data);

        if ($step === 4 && $request->boolean('is_final')) {
            $application->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);
            return Redirect::route('application.submitted');
        }

        return Redirect::route('application.show');
    }

    // ──────────────────────────────────────────────
    // Page de confirmation
    // ──────────────────────────────────────────────
    public function submitted(): Response
    {
        return Inertia::render('Application/Submitted');
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────
    private function getOrCreate(): Application
    {
        return Application::firstOrCreate(
            ['user_id' => Auth::id()],
            ['current_step' => 1, 'status' => 'draft']
        );
    }

    private function validateStep1(Request $request): array
    {
        return $request->validate([
            'gender'               => ['required', 'in:M,F'],
            'country_of_residence' => ['required', 'string', 'max:191'],
            'city_of_residence'    => ['required', 'string', 'max:191'],
            'postal_address'       => ['required', 'string', 'max:500'],
            'personal_email'       => ['required', 'email', 'max:191'],
            'whatsapp_phone'       => ['required', 'string', 'max:50'],
            'has_internet_access'  => ['required', 'boolean'],
            'current_status'       => ['required', 'in:student,employee,entrepreneur,unemployed'],
            'university_name'      => ['nullable', 'string', 'max:191'],
            'weekly_hours'         => ['required', 'numeric', 'min:0', 'max:168'],
        ]);
    }

    private function validateStep2(Request $request): array
    {
        return $request->validate([
            'project_name'        => ['required', 'string', 'max:191'],
            'project_problem'     => ['required', 'string'],
            'project_solution'    => ['required', 'string'],
            'project_sector'      => ['required', 'string', 'max:191'],
            'project_competition' => ['required', 'string'],
            'project_pitch'       => ['required', 'string', 'max:500'],
            'project_stage'       => ['required', 'in:wish,idea,business_model,funding,legal'],
            'team_project'        => ['required', 'boolean'],
        ]);
    }

    private function validateStep3(Request $request, Application $application): array
    {
        $data = $request->validate([
            'cv_file'      => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:1024'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
        ]);

        if ($request->hasFile('cv_file')) {
            if ($application->cv_file) {
                Storage::disk('public')->delete($application->cv_file);
            }
            $data['cv_file'] = $request->file('cv_file')->store('applications/cv', 'public');
        } else {
            unset($data['cv_file']);
        }

        // Attestation universitaire (optionnelle)
        if ($request->hasFile('university_doc')) {
            $request->validate(['university_doc' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:1024']]);
            if ($application->university_doc) {
                Storage::disk('public')->delete($application->university_doc);
            }
            $data['university_doc'] = $request->file('university_doc')->store('applications/docs', 'public');
        }

        return $data;
    }

    private function validateStep4(Request $request): array
    {
        return $request->validate([
            'previous_program' => ['required', 'boolean'],
            'support_needs'    => ['required', 'array', 'min:1'],
            'support_needs.*'  => ['string'],
            'heard_about_us'   => ['required', 'string', 'max:500'],
            'consent_data'     => ['accepted'],
            'consent_image'    => ['accepted'],
        ]);
    }
}
