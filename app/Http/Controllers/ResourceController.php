<?php
namespace App\Http\Controllers;

use App\Models\ResourceCategory;
use Inertia\Inertia;
use Inertia\Response;

class ResourceController extends Controller
{
    public function index(): Response
    {
        $categories = ResourceCategory::orderBy('sort_order')
            ->with(['resources' => fn ($q) => $q->where('is_published', true)->latest()])
            ->get();

        return Inertia::render('Resources/Index', compact('categories'));
    }
}
