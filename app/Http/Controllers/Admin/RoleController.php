<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Roles/Index', [
            'roles' => Role::withCount('users')->with('permissions:id,name')->orderBy('name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', [
            'permissions' => Permission::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create($request->safe()->only(['name', 'slug']));
        $role->permissions()->sync($request->input('permissions', []));

        return Redirect::route('admin.roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role): Response
    {
        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role->load('permissions:id'),
            'permissions' => Permission::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->safe()->only(['name', 'slug']));
        $role->permissions()->sync($request->input('permissions', []));

        return Redirect::route('admin.roles.index')->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            return Redirect::route('admin.roles.index')->with('error', 'Impossible de supprimer un rôle encore attribué à des utilisateurs.');
        }

        $role->delete();

        return Redirect::route('admin.roles.index')->with('success', 'Rôle supprimé avec succès.');
    }
}
