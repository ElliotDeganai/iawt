<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AgendaController extends Controller
{
    public function index(Request $request): Response
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        $events = Event::with('category')
            ->where('is_published', true)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->map(fn ($e) => [
                'id'                => $e->id,
                'title'             => $e->title,
                'description'       => $e->description,
                'image'             => $e->image ? "/storage/{$e->image}" : null,
                'url'               => $e->url,
                'date'              => $e->date->format('Y-m-d'),
                'time'              => $e->time ? substr($e->time, 0, 5) : null,
                'is_featured'       => $e->is_featured,
                'event_category_id' => $e->event_category_id,
                'category'          => $e->category ? [
                    'id'    => $e->category->id,
                    'name'  => $e->category->name,
                    'color' => $e->category->color,
                ] : null,
            ]);

        return Inertia::render('Agenda/Index', [
            'events'     => $events,
            'categories' => EventCategory::orderBy('sort_order')->get(['id', 'name', 'color']),
            'year'       => $year,
            'month'      => $month,
        ]);
    }
}