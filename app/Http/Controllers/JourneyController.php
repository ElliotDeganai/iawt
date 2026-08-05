<?php

namespace App\Http\Controllers;

use App\Models\JourneyStep;
use Inertia\Inertia;
use Inertia\Response;

class JourneyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Journey/Index', [
            'steps' => JourneyStep::orderBy('position')->get(),
        ]);
    }
}
