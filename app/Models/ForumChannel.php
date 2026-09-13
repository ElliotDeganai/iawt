<?php
namespace App\Models;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class ForumChannel extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order', 'is_active', 'moderation_mode', 'topic_creation'];
    protected $casts = ['is_active' => 'boolean'];

    public function posts() { return $this->hasMany(ForumPost::class, 'channel_id'); }
    public function latestPost() { return $this->hasOne(ForumPost::class, 'channel_id')->whereNull('parent_id')->where('status', 'approved')->latest(); }

    /**
     * Effective moderation mode: channel override or global setting.
     */
    public function effectiveModerationMode(): string
    {
        if ($this->moderation_mode && $this->moderation_mode !== 'inherit') {
            return $this->moderation_mode;
        }
        return Setting::get('forum_moderation_mode', 'strict');
    }
}
