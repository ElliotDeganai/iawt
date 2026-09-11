<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumChannel extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function posts() { return $this->hasMany(ForumPost::class, 'channel_id'); }
    public function latestPost() { return $this->hasOne(ForumPost::class, 'channel_id')->whereNull('parent_id')->latest(); }
}
