<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:events.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Events/Index', [
            'events' => Event::orderBy('date')->orderBy('time')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Events/Create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = ImageService::store($request->file('image'), 'events');
        }

        Event::create($data);

        return Redirect::route('admin.events.index')
            ->with('success', 'Événement créé avec succès.');
    }

    public function edit(Event $event): Response
    {
        return Inertia::render('Admin/Events/Edit', [
            'event' => $event,
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('remove_image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $data['image'] = ImageService::store($request->file('image'), 'events');
        } else {
            unset($data['image']);
        }

        unset($data['remove_image']);
        $event->update($data);

        return Redirect::route('admin.events.index')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();

        return Redirect::route('admin.events.index')
            ->with('success', 'Événement supprimé.');
    }
}