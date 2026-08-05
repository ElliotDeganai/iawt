<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryMedia extends Model
{
    protected $table = 'country_media';

    protected $fillable = [
        'featured_country_id', 'type', 'path', 'video_url', 'caption', 'position',
    ];

    protected $appends = ['url'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(FeaturedCountry::class, 'featured_country_id');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->type === 'photo' && $this->path) {
            return '/storage/' . $this->path;
        }
        return $this->video_url;
    }
}
