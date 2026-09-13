<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumChannel;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ForumChannelController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/ForumChannels/Index', [
            'globalMode' => Setting::get('forum_moderation_mode', 'strict'),
            'channels' => ForumChannel::orderBy('sort_order')
                ->withCount(['posts as topics_count' => fn ($q) => $q->whereNull('parent_id')])
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
            'moderation_mode' => ['nullable', 'in:inherit,strict,soft'],
            'topic_creation'  => ['nullable', 'in:everyone,admin_only'],
        ]);
        $data['moderation_mode'] = $data['moderation_mode'] ?? 'inherit';
        $data['topic_creation'] = $data['topic_creation'] ?? 'everyone';
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? ForumChannel::max('sort_order') + 1;

        ForumChannel::create($data);
        return Redirect::route('admin.forum-channels.index')->with('success', 'Espace créé.');
    }

    public function update(Request $request, ForumChannel $forumChannel): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['boolean'],
            'moderation_mode' => ['nullable', 'in:inherit,strict,soft'],
            'topic_creation'  => ['nullable', 'in:everyone,admin_only'],
        ]);
        $data['moderation_mode'] = $data['moderation_mode'] ?? 'inherit';
        $data['topic_creation'] = $data['topic_creation'] ?? 'everyone';
        $data['slug'] = Str::slug($data['name']);

        $forumChannel->update($data);
        return Redirect::route('admin.forum-channels.index')->with('success', 'Espace mis à jour.');
    }

    public function destroy(ForumChannel $forumChannel): RedirectResponse
    {
        if ($forumChannel->posts()->count()) {
            return Redirect::route('admin.forum-channels.index')->with('error', 'Impossible de supprimer un espace qui contient des publications.');
        }
        $forumChannel->delete();
        return Redirect::route('admin.forum-channels.index')->with('success', 'Espace supprimé.');
    }
}
