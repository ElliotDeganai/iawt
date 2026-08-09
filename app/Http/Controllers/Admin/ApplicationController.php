<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminComment;
use App\Models\Application;
use App\Models\JourneyStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:applications.manage');
    }

    /**
     * Liste de toutes les candidatures.
     */
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
                      $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $query->get(),
            'filters'      => $request->only('status', 'search'),
        ]);
    }

    /**
     * Fiche complète d'une candidature (onglets).
     */
    public function show(Application $application): Response
    {
        $application->load(['user' => fn ($q) => $q->withTrashed(), 'comments.author']);

        return Inertia::render('Admin/Applications/Show', [
            'application'  => $application,
            'journeySteps' => JourneyStep::orderBy('position')->get(),
        ]);
    }

    /**
     * Accepter une candidature.
     */
    public function accept(Application $application): RedirectResponse
    {
        $application->update([
            'status'               => 'accepted',
            'journey_current_step' => 1,
        ]);

        return Redirect::route('admin.applications.show', $application)
            ->with('success', 'Candidature acceptée — l\'étape 1 du parcours a été débloquée.');
    }

    /**
     * Rejeter une candidature (avec commentaire obligatoire).
     */
    public function reject(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $application->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Ajouter le commentaire de rejet automatiquement
        AdminComment::create([
            'application_id' => $application->id,
            'user_id'        => Auth::id(),
            'body'           => "Candidature rejetée : {$request->rejection_reason}",
        ]);

        return Redirect::route('admin.applications.show', $application)
            ->with('success', 'Candidature rejetée.');
    }

    /**
     * Débloquer une étape du parcours.
     */
    public function unlockStep(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['step' => ['required', 'integer', 'min:1', 'max:7']]);

        if ($application->status !== 'accepted') {
            return Redirect::back()->with('error', 'La candidature doit être acceptée pour débloquer une étape.');
        }

        $step = (int) $request->step;
        if ($step > $application->journey_current_step) {
            $application->update(['journey_current_step' => $step]);
        }

        return Redirect::route('admin.applications.show', $application)
            ->with('success', "Étape {$step} débloquée.");
    }

    /**
     * Ajouter un commentaire admin.
     */
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
}