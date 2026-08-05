<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'url',
        'date', 'time', 'is_featured', 'is_published',
    ];

    protected $casts = [
        'date'        => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];
}
