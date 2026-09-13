<?php
namespace App\Http\Controllers;

use App\Models\ForumChannel;
use App\Models\ForumPost;
use App\Models\ForumPostAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ForumController extends Controller
{
    public function index(): Response
    {
        $channels = ForumChannel::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['posts as topics_count' => fn ($q) => $q->whereNull('parent_id')->approved()])
            ->withCount(['posts as replies_count' => fn ($q) => $q->whereNotNull('parent_id')->approved()])
            ->with(['latestPost' => fn ($q) => $q->approved()->with('user:id,first_name,last_name,avatar')])
            ->get();

        return Inertia::render('Forum/Index', compact('channels'));
    }

    public function show(ForumChannel $channel): Response
    {
        $userId = Auth::id();

        $posts = ForumPost::where('channel_id', $channel->id)
            ->whereNull('parent_id')
            ->where(fn ($q) => $q->approved()->orWhere('user_id', $userId))
            ->with(['user:id,first_name,last_name,avatar', 'attachments'])
            ->withCount(['approvedReplies as replies_count'])
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(20);

        return Inertia::render('Forum/Channel', compact('channel', 'posts'));
    }

    public function showPost(ForumChannel $channel, ForumPost $post): Response
    {
        abort_if($post->status !== 'approved' && $post->user_id !== Auth::id(), 404);

        $post->load([
            'user:id,first_name,last_name,avatar',
            'attachments',
            'replies' => fn ($q) => $q->with(['user:id,first_name,last_name,avatar', 'attachments'])->oldest(),
        ]);

        return Inertia::render('Forum/Post', compact('channel', 'post'));
    }

    public function store(Request $request, ForumChannel $channel): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string', 'max:10000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240'],
            'links' => ['nullable', 'array', 'max:3'],
            'links.*' => ['url'],
        ]);

        $post = ForumPost::create([
            'channel_id' => $channel->id,
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'body'       => $request->body,
            'status'     => (Auth::user()->isAdmin() || \App\Models\Setting::get('forum_moderation_mode', 'strict') === 'soft') ? 'approved' : 'pending',
        ]);

        $this->handleAttachments($post, $request);

        $message = $post->status === 'approved'
            ? 'Votre publication est en ligne.'
            : 'Votre publication a été soumise et sera visible après validation par un administrateur.';

        return Redirect::route('forum.channel', $channel)->with('success', $message);
    }

    public function reply(Request $request, ForumChannel $channel, ForumPost $post): RedirectResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'max:10000'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'max:10240'],
        ]);

        $reply = ForumPost::create([
            'channel_id' => $channel->id,
            'user_id'    => Auth::id(),
            'body'       => $request->body,
            'parent_id'  => $post->id,
            'status'     => (Auth::user()->isAdmin() || \App\Models\Setting::get('forum_moderation_mode', 'strict') === 'soft') ? 'approved' : 'pending',
        ]);

        $this->handleAttachments($reply, $request);

        $message = $reply->status === 'approved'
            ? 'Votre réponse est publiée.'
            : 'Votre réponse a été soumise et sera visible après validation.';

        return Redirect::route('forum.post', [$channel, $post])->with('success', $message);
    }

    private function handleAttachments(ForumPost $post, Request $request): void
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $type = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document';
                ForumPostAttachment::create([
                    'post_id'       => $post->id,
                    'type'          => $type,
                    'path'          => $file->store('forum', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }
        if ($request->links) {
            foreach ($request->links as $url) {
                ForumPostAttachment::create([
                    'post_id' => $post->id,
                    'type'    => str_contains($url, 'youtu') ? 'video' : 'link',
                    'url'     => $url,
                ]);
            }
        }
    }
}