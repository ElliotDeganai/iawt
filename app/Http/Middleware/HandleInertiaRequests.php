<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Setting;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'siteSettings' => [
                'name' => Setting::get('site_name', 'InAfrikaWeTrust'),
                'tagline' => Setting::get('tagline', ''),
                'logo' => Setting::get('logo', '/images/logo-default.svg'),
            ],
            'auth' => [
                'user' => $user,
                'permissions' => $user?->permissionSlugs() ?? [],
                'isAdmin' => $user?->isAdmin() ?? false,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
