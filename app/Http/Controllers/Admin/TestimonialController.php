<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home_content.manage');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'author_name'  => ['required', 'string', 'max:191'],
            'author_meta'  => ['nullable', 'string', 'max:191'],
            'author_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'quote'        => ['required', 'string', 'max:2000'],
            'is_approved'  => ['boolean'],
            'position'     => ['required', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('author_photo')) {
            $data['author_photo'] = ImageService::store($request->file('author_photo'), 'testimonials', 800, 800);
        }

        Testimonial::create($data);

        return Redirect::route('admin.testimonials.index')->with('success', 'Témoignage ajouté.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validate([
            'author_name'   => ['required', 'string', 'max:191'],
            'author_meta'   => ['nullable', 'string', 'max:191'],
            'author_photo'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'remove_photo'  => ['boolean'],
            'quote'         => ['required', 'string', 'max:2000'],
            'is_approved'   => ['boolean'],
            'position'      => ['required', 'integer', 'min:0'],
        ]);

        if ($request->boolean('remove_photo')) {
            if ($testimonial->author_photo) Storage::disk('public')->delete($testimonial->author_photo);
            $data['author_photo'] = null;
        } elseif ($request->hasFile('author_photo')) {
            if ($testimonial->author_photo) Storage::disk('public')->delete($testimonial->author_photo);
            $data['author_photo'] = ImageService::store($request->file('author_photo'), 'testimonials', 800, 800);
        }

        unset($data['remove_photo']);
        $testimonial->update($data);

        return Redirect::route('admin.testimonials.index')->with('success', 'Témoignage mis à jour.');
    }

    public function toggleApproval(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_approved' => !$testimonial->is_approved]);

        return Redirect::route('admin.testimonials.index')
            ->with('success', $testimonial->is_approved ? 'Témoignage approuvé.' : 'Témoignage masqué.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->author_photo) Storage::disk('public')->delete($testimonial->author_photo);
        $testimonial->delete();

        return Redirect::route('admin.testimonials.index')->with('success', 'Témoignage supprimé.');
    }
}
