<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class StatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home_content.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Stats/Index', [
            'stats' => Stat::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'value'    => ['required', 'string', 'max:50'],
            'label'    => ['required', 'string', 'max:191'],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        Stat::create($data);

        return Redirect::route('admin.stats.index')->with('success', 'Chiffre clé ajouté.');
    }

    public function update(Request $request, Stat $stat): RedirectResponse
    {
        $data = $request->validate([
            'value'    => ['required', 'string', 'max:50'],
            'label'    => ['required', 'string', 'max:191'],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $stat->update($data);

        return Redirect::route('admin.stats.index')->with('success', 'Chiffre clé mis à jour.');
    }

    public function destroy(Stat $stat): RedirectResponse
    {
        $stat->delete();
        return Redirect::route('admin.stats.index')->with('success', 'Chiffre clé supprimé.');
    }
}
