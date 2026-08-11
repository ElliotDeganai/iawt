<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JourneyResponse;
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

        $journeyResponses = JourneyResponse::where('user_id', Auth::id())
            ->get()
            ->keyBy('step_number');

        return Inertia::render('Dashboard', [
            'application'      => $application,
            'steps'            => $steps,
            'journeyResponses' => $journeyResponses,
        ]);
    }
}
