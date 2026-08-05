<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryPlace extends Model
{
    protected $fillable = [
        'featured_country_id', 'place_category_id',
        'name', 'description', 'link', 'position',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(FeaturedCountry::class, 'featured_country_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'place_category_id');
    }
}
