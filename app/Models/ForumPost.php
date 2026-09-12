<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumPost extends Model
{
    protected $fillable = ['channel_id', 'user_id', 'title', 'body', 'parent_id', 'is_pinned', 'status'];
    protected $casts = ['is_pinned' => 'boolean'];

    public function channel(): BelongsTo { return $this->belongsTo(ForumChannel::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function parent(): BelongsTo { return $this->belongsTo(ForumPost::class, 'parent_id'); }
    public function replies(): HasMany { return $this->hasMany(ForumPost::class, 'parent_id'); }
    public function approvedReplies(): HasMany { return $this->hasMany(ForumPost::class, 'parent_id')->where('status', 'approved'); }
    public function attachments(): HasMany { return $this->hasMany(ForumPostAttachment::class, 'post_id'); }

    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopePending($q) { return $q->where('status', 'pending'); }
}
