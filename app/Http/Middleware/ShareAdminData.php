<?php

namespace App\Http\Middleware;

use App\Models\Application;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareAdminData
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin()) {
            Inertia::share('pendingApplicationsCount', fn () =>
                Application::where('status', 'submitted')->count()
            );
        }

        return $next($request);
    }
}
