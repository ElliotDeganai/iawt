<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JourneyStep;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $application = Application::where('user_id', Auth::id())->first();
        $steps       = JourneyStep::orderBy('position')->get();

        return Inertia::render('Dashboard', [
            'application' => $application,
            'steps'       => $steps,
        ]);
    }
}
