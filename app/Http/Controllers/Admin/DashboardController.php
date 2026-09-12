<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ForumPost;
use App\Models\JourneyResponse;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Pending applications (submitted, not yet accepted/rejected)
        $pendingApplications = Application::where('status', 'submitted')
            ->with('user:id,first_name,last_name,email')
            ->latest('submitted_at')
            ->get();

        // Steps completed but not yet validated
        $pendingSteps = JourneyResponse::whereNotNull('completed_at')
            ->whereNull('validated_at')
            ->whereNull('rework_reason')
            ->with('user:id,first_name,last_name,email')
            ->latest('completed_at')
            ->get()
            ->map(fn ($r) => [
                'id'            => $r->id,
                'user_id'       => $r->user_id,
                'user'          => $r->user,
                'step_number'   => $r->step_number,
                'completed_at'  => $r->completed_at,
                'application_id'=> Application::where('user_id', $r->user_id)->value('id'),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalUsers'            => User::count(),
                'totalCandidates'       => User::whereNull('role_id')->count(),
                'totalStaff'            => User::whereNotNull('role_id')->count(),
                'totalRoles'            => Role::count(),
                'pendingApplications'   => $pendingApplications->count(),
                'pendingSteps'          => $pendingSteps->count(),
                'acceptedApplications'  => Application::where('status', 'accepted')->count(),
            ],
            'pendingApplications' => $pendingApplications,
            'pendingSteps'        => $pendingSteps,
            'recentUsers'         => User::latest()->take(5)->get(['id', 'first_name', 'last_name', 'email', 'created_at']),
        ]);
    }
}
