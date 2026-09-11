<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CountrySheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CountrySheetController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/CountrySheets/Index', [
            'sheets' => CountrySheet::orderBy('country_name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/CountrySheets/Edit', [
            'sheet' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        CountrySheet::create($data);
        return Redirect::route('admin.country-sheets.index')->with('success', 'Fiche pays créée.');
    }

    public function edit(CountrySheet $countrySheet): Response
    {
        return Inertia::render('Admin/CountrySheets/Edit', [
            'sheet' => $countrySheet,
        ]);
    }

    public function update(Request $request, CountrySheet $countrySheet): RedirectResponse
    {
        $data = $this->validated($request);
        $countrySheet->update($data);
        return Redirect::route('admin.country-sheets.index')->with('success', 'Fiche pays mise à jour.');
    }

    public function destroy(CountrySheet $countrySheet): RedirectResponse
    {
        $countrySheet->delete();
        return Redirect::route('admin.country-sheets.index')->with('success', 'Fiche pays supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'country_name'     => ['required', 'string', 'max:191'],
            'flag_emoji'       => ['nullable', 'string', 'max:10'],
            'title'            => ['required', 'string', 'max:500'],
            'intro'            => ['nullable', 'string'],
            'warning'          => ['nullable', 'string'],
            'actors'           => ['nullable', 'array'],
            'actors.*.name'    => ['required', 'string'],
            'actors.*.role'    => ['required', 'string'],
            'organism_name'    => ['nullable', 'string', 'max:191'],
            'organism_full'    => ['nullable', 'string', 'max:500'],
            'organism_desc'    => ['nullable', 'string'],
            'organism_address' => ['nullable', 'string', 'max:500'],
            'organism_hours'   => ['nullable', 'string', 'max:191'],
            'organism_website' => ['nullable', 'string', 'max:191'],
            'documents'        => ['nullable', 'array'],
            'documents.*'      => ['string'],
            'individual_steps' => ['nullable', 'array'],
            'individual_steps.*' => ['string'],
            'company_steps'    => ['nullable', 'array'],
            'company_steps.*'  => ['string'],
            'fees'             => ['nullable', 'string'],
            'sector_auth'      => ['nullable', 'string'],
            'is_published'     => ['boolean'],
        ]);
    }
}
