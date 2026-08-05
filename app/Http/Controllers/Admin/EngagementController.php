<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEngagementRequest;
use App\Http\Requests\Admin\UpdateEngagementRequest;
use App\Models\Engagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class EngagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home_content.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Engagements/Index', [
            'engagements' => Engagement::orderBy('position')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Engagements/Create');
    }

    public function store(StoreEngagementRequest $request): RedirectResponse
    {
        Engagement::create($request->validated());

        return Redirect::route('admin.engagements.index')->with('success', 'Engagement créé avec succès.');
    }

    public function edit(Engagement $engagement): Response
    {
        return Inertia::render('Admin/Engagements/Edit', [
            'engagement' => $engagement,
        ]);
    }

    public function update(UpdateEngagementRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->update($request->validated());

        return Redirect::route('admin.engagements.index')->with('success', 'Engagement mis à jour avec succès.');
    }

    public function destroy(Engagement $engagement): RedirectResponse
    {
        $engagement->delete();

        return Redirect::route('admin.engagements.index')->with('success', 'Engagement supprimé avec succès.');
    }
}
