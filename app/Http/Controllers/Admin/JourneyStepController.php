<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJourneyStepRequest;
use App\Http\Requests\Admin\UpdateJourneyStepRequest;
use App\Models\JourneyStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class JourneyStepController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home_content.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/JourneySteps/Index', [
            'steps' => JourneyStep::orderBy('position')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/JourneySteps/Create');
    }

    public function store(StoreJourneyStepRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('journey-steps', 'public');
        }

        JourneyStep::create($data);

        return Redirect::route('admin.journey-steps.index')
            ->with('success', 'Étape créée avec succès.');
    }

    public function edit(JourneyStep $journeyStep): Response
    {
        return Inertia::render('Admin/JourneySteps/Edit', [
            'step' => $journeyStep,
        ]);
    }

    public function update(UpdateJourneyStepRequest $request, JourneyStep $journeyStep): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('remove_image')) {
            if ($journeyStep->image) {
                Storage::disk('public')->delete($journeyStep->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($journeyStep->image) {
                Storage::disk('public')->delete($journeyStep->image);
            }
            $data['image'] = $request->file('image')->store('journey-steps', 'public');
        }

        unset($data['remove_image']);
        $journeyStep->update($data);

        return Redirect::route('admin.journey-steps.index')
            ->with('success', 'Étape mise à jour avec succès.');
    }

    public function destroy(JourneyStep $journeyStep): RedirectResponse
    {
        if ($journeyStep->image) {
            Storage::disk('public')->delete($journeyStep->image);
        }

        $journeyStep->delete();

        return Redirect::route('admin.journey-steps.index')
            ->with('success', 'Étape supprimée avec succès.');
    }
}
