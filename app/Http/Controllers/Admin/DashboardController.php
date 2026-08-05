<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalUsers' => User::count(),
                'totalCandidates' => User::whereNull('role_id')->count(),
                'totalStaff' => User::whereNotNull('role_id')->count(),
                'totalRoles' => Role::count(),
            ],
            'recentUsers' => User::latest()->take(5)->get(['id', 'first_name', 'last_name', 'email', 'created_at']),
        ]);
    }
}
