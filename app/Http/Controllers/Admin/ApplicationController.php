<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminComment;
use App\Models\Application;
use App\Models\JourneyResponse;
use App\Models\JourneyStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Notifications\ApplicationResult;
use App\Notifications\StepRework;
use App\Notifications\StepValidated;
use Inertia\Response;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:applications.manage');
    }

    public function index(Request $request): Response
    {
        $query = Application::with(['user' => fn ($q) => $q->withTrashed()])
            ->orderByRaw("FIELD(status, 'submitted', 'accepted', 'rejected', 'draft')")
            ->orderByDesc('submitted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->withTrashed()
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $query->get(),
            'filters'      => $request->only('status', 'search'),
            'totalSteps'   => JourneyStep::count(),
        ]);
    }

    public function show(Application $application): Response
    {
        $application->load(['user' => fn ($q) => $q->withTrashed(), 'comments.author']);

        $journeyResponses = JourneyResponse::where('user_id', $application->user_id)
            ->orderBy('step_number')
            ->get()
            ->keyBy('step_number');

        return Inertia::render('Admin/Applications/Show', [
            'canFastForward'   => app()->isLocal() || (Auth::user() && Auth::user()->isAdmin()),
            'application'      => $application,
            'journeySteps'     => JourneyStep::orderBy('position')->get(),
            'journeyResponses' => $journeyResponses,
        ]);
    }

    public function accept(Application $application): RedirectResponse
    {
        $application->update([
            'status'               => 'accepted',
            'journey_current_step' => 1,
        ]);

        $application->user->notify(new ApplicationResult($application, 'accepted'));

        return Redirect::route('admin.applications.show', $application)
            ->with('success', 'Candidature acceptée — l\'étape 1 du parcours a été débloquée.');
    }

    public function reject(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $application->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        AdminComment::create([
            'application_id' => $application->id,
            'user_id'        => Auth::id(),
            'body'           => "Candidature rejetée : {$request->rejection_reason}",
        ]);

        $application->load('user');
        $application->user->notify(new ApplicationResult($application, 'rejected'));

        return Redirect::route('admin.applications.show', $application)
            ->with('success', 'Candidature rejetée.');
    }

    public function unlockStep(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['step' => ['required', 'integer', 'min:1', 'max:8']]);

        if ($application->status !== 'accepted') {
            return Redirect::back()->with('error', 'La candidature doit être acceptée.');
        }

        $step = (int) $request->step;
        if ($step > $application->journey_current_step) {
            $application->update(['journey_current_step' => $step]);
        }

        return Redirect::route('admin.applications.show', $application)
            ->with('success', "Étape {$step} débloquée.");
    }

    public function validateStep(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['step' => ['required', 'integer', 'min:1', 'max:8']]);

        $step = (int) $request->step;

        $response = JourneyResponse::where('user_id', $application->user_id)
            ->where('step_number', $step)
            ->whereNotNull('completed_at')
            ->first();

        if (!$response) {
            return Redirect::back()->with('error', "Le candidat n'a pas encore soumis l'étape {$step}.");
        }

        $response->update(['validated_at' => now()]);

        // Débloquer l'étape suivante
        $nextStep = $step + 1;
        if ($nextStep <= 8 && $nextStep > $application->journey_current_step) {
            $application->update(['journey_current_step' => $nextStep]);
        }

        // Notify candidate
        $application->load('user');
        $application->user->notify(new StepValidated($step, $step >= 8));

        $message = $step < 8
            ? "Étape {$step} validée — l'étape {$nextStep} a été débloquée."
            : "Étape {$step} validée — le parcours est terminé. Félicitations au candidat !";

        return Redirect::route('admin.applications.show', $application)
            ->with('success', $message);
    }

    public function reworkStep(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'step'   => ['required', 'integer', 'min:1', 'max:8'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $step = (int) $request->step;

        $response = JourneyResponse::where('user_id', $application->user_id)
            ->where('step_number', $step)
            ->first();

        if (!$response) {
            return Redirect::back()->with('error', "Aucune réponse trouvée pour l'étape {$step}.");
        }

        $response->update([
            'completed_at'  => null,
            'validated_at'  => null,
            'rework_reason' => $request->reason,
        ]);

        AdminComment::create([
            'application_id' => $application->id,
            'user_id'        => Auth::id(),
            'body'           => "Étape {$step} à retravailler : {$request->reason}",
        ]);

        // Notify candidate
        $application->load('user');
        $application->user->notify(new StepRework($step, $request->reason));

        return Redirect::route('admin.applications.show', $application)
            ->with('success', "Étape {$step} renvoyée au candidat pour révision.");
    }

    public function comment(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['body' => ['required', 'string', 'max:2000']]);

        AdminComment::create([
            'application_id' => $application->id,
            'user_id'        => Auth::id(),
            'body'           => $request->body,
        ]);

        return Redirect::route('admin.applications.show', $application)
            ->with('success', 'Commentaire ajouté.');
    }

    public function fastForward(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['step' => ['required', 'integer', 'min:1', 'max:8']]);

        $target = (int) $request->step;

        \DB::transaction(function () use ($application, $target) {
            if ($application->status !== 'accepted') {
                $application->update(['status' => 'accepted']);
            }

            $now = now();
            $userId = $application->user_id;
            $dummyData = [
                1 => [
                    'origins' => ['Observation d\'un besoin'],
                    'who' => 'Porteur de projet test',
                    'what' => 'Produits agricoles transformés',
                    'why' => 'Répondre à un besoin local',
                    'how' => 'Production et vente directe',
                    'where' => 'Côte d\'Ivoire',
                    'adequation' => 4,
                ],
                2 => [
                    'project_name' => 'Projet test',
                    'project_holders' => 'Porteur test',
                    'zone_country' => 'Côte d\'Ivoire',
                    'zone_region' => 'Lagunes',
                    'zone_city' => 'Abidjan',
                    'description' => 'Transformation et vente de produits agricoles locaux.',
                    'problem' => 'Manque de produits transformés accessibles.',
                    'main_client' => 'Consommateurs urbains 25-45 ans',
                    'decision' => 'Je poursuis le projet sans modification majeure',
                    'client_age' => '25-45 ans',
                    'client_situation' => 'Actifs urbains',
                    'client_activity' => 'Salariés et entrepreneurs',
                ],
                3 => [
                    'value_prop' => 'Produits locaux transformés, sains et accessibles',
                    'segments' => 'Consommateurs urbains, restaurants, hôtels',
                    'partners' => 'Producteurs agricoles locaux',
                    'activities' => 'Transformation, conditionnement, distribution',
                    'rel_client' => 'Vente directe et livraison',
                    'resources' => 'Atelier de transformation, équipement',
                    'channels' => 'Points de vente, marchés, réseaux sociaux',
                    'costs' => 'Matières premières, loyer, transport',
                    'revenue' => 'Vente directe et commandes en gros',
                ],
                4 => [
                    'n_Frais d\'immatriculation et de création' => 75000,
                    'n_Achat de matériel et équipement' => 500000,
                    'n_Besoin en fonds de roulement de démarrage' => 200000,
                    'r_Apport personnel' => 400000,
                    'r_Prêt d\'honneur' => 375000,
                    'p_CA — ventes de produits_y1' => 1200000,
                    'p_CA — ventes de produits_y2' => 2400000,
                    'p_CA — ventes de produits_y3' => 3600000,
                    'c_Achats de marchandises / matières premières_y1' => 480000,
                    'c_Loyer et charges locatives_y1' => 120000,
                ],
                5 => [
                    'country' => 'Côte d\'Ivoire',
                    'city' => 'Abidjan',
                    'status' => 'Sans activité',
                    'experience' => 'Moins de 2 ans',
                    'project_name' => 'Projet test',
                    'sector' => 'Agro-transformation',
                    'project_type' => 'Individuel',
                    'founders' => '1',
                    'investment' => 'Moyen',
                    'bank_need' => 'Faible',
                    'legal_form' => 'SARLU',
                    'Q1' => '1', 'Q2' => 'Oui', 'Q3' => 'Important',
                ],
                6 => [
                    'holder_name' => 'Porteur test',
                    'project_name' => 'Projet test',
                    'sector' => 'Agriculture',
                    'host_company' => 'Ferme Modèle Abidjan',
                    'tested_activity' => 'Transformation de manioc',
                    'date_start' => '2026-06-01',
                    'date_end' => '2026-06-21',
                    'days_count' => '15',
                    'how_found' => ['Réseau personnel'],
                    'why_company' => 'Proximité et secteur similaire',
                    'company_activity' => 'Production agricole',
                    'activities_done' => ['Production', 'Vente'],
                    'revenue' => 150000,
                    'expense_purchases' => 60000,
                    'found_clients' => 'Oui',
                    'client_count' => '12',
                    'assessment' => 'Prêt à être lancé',
                    'assessment_why' => 'Résultats encourageants',
                    'next_30_days' => 'Finaliser le business plan',
                    'support_needs' => ['Financement'],
                ],
                7 => [
                    'creation_country' => 'Côte d\'Ivoire',
                    'track' => 'individual',
                    'checklist' => ['Documents d\'identité', 'Statuts rédigés'],
                    'notes' => 'RAS',
                ],
                8 => [
                    'checklist' => ['Fiche profil complétée', 'Questionnaire complété', 'Forme juridique choisie'],
                ],
            ];

            // Delete ALL existing responses (clean slate)
            JourneyResponse::where('user_id', $userId)->delete();

            // Bulk insert only steps before target (completed + validated)
            $rows = [];
            for ($s = 1; $s < $target; $s++) {
                $rows[] = [
                    'user_id'      => $userId,
                    'step_number'  => $s,
                    'data'         => json_encode($dummyData[$s] ?? []),
                    'completed_at' => $now,
                    'validated_at' => $now,
                    'rework_reason'=> null,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
            if ($rows) {
                JourneyResponse::insert($rows);
            }

            // Set current step to target (can go forward or backward)
            $application->update(['journey_current_step' => $target]);
        });

        $label = $target > 1
            ? "Fast-forward : étapes 1–" . ($target - 1) . " validées, étape {$target} débloquée."
            : "Reset : toutes les étapes effacées, étape 1 débloquée.";

        return Redirect::route('admin.applications.show', $application)
            ->with('success', $label);
    }
}