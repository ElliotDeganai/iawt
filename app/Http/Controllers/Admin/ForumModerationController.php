<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ForumModerationController extends Controller
{
    public function index(Request $request): Response
    {
        $query = ForumPost::with(['user:id,first_name,last_name,email,avatar', 'channel:id,name,slug', 'attachments'])
            ->withCount('replies');

        if ($request->channel) $query->where('channel_id', $request->channel);

        if ($request->filter === 'topics') $query->whereNull('parent_id');
        elseif ($request->filter === 'replies') $query->whereNotNull('parent_id');

        if ($request->status) $query->where('status', $request->status);
        else $query->where('status', 'pending'); // par défaut : en attente

        if ($request->search) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('body', 'like', "%{$search}%"));
        }

        return Inertia::render('Admin/ForumModeration/Index', [
            'posts'    => $query->latest()->paginate(30)->withQueryString(),
            'channels' => ForumChannel::orderBy('sort_order')->get(['id', 'name']),
            'filters'  => $request->only(['channel', 'filter', 'search', 'status']),
            'counts'   => [
                'pending'  => ForumPost::where('status', 'pending')->count(),
                'approved' => ForumPost::where('status', 'approved')->count(),
                'hidden'   => ForumPost::where('status', 'hidden')->count(),
            ],
        ]);
    }

    public function approve(ForumPost $post): RedirectResponse
    {
        $post->update(['status' => 'approved']);
        return Redirect::back()->with('success', 'Publication approuvée.');
    }

    public function hide(ForumPost $post): RedirectResponse
    {
        $post->update(['status' => 'hidden']);
        return Redirect::back()->with('success', 'Publication masquée.');
    }

    public function togglePin(ForumPost $post): RedirectResponse
    {
        $post->update(['is_pinned' => !$post->is_pinned]);
        return Redirect::back()->with('success', $post->is_pinned ? 'Épinglée.' : 'Désépinglée.');
    }

    public function destroy(ForumPost $post): RedirectResponse
    {
        foreach ($post->attachments as $a) { if ($a->path) Storage::disk('public')->delete($a->path); }
        foreach ($post->replies as $reply) { foreach ($reply->attachments as $a) { if ($a->path) Storage::disk('public')->delete($a->path); } }
        $post->delete();
        return Redirect::back()->with('success', 'Publication supprimée.');
    }
}
