<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumPostAttachment extends Model
{
    protected $fillable = ['post_id', 'type', 'path', 'url', 'original_name'];
}
