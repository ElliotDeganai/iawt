<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['category_id', 'user_id', 'title', 'description', 'type', 'file_path', 'url', 'thumbnail', 'is_published'];
    protected $casts = ['is_published' => 'boolean'];

    public function category() { return $this->belongsTo(ResourceCategory::class); }
    public function user() { return $this->belongsTo(User::class); }
}