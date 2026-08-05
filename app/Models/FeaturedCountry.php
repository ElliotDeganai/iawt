<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'flag_emoji', 'flag_image', 'flag_code', 'tags', 'summary',
        'content', 'cover_image', 'cover_media_id', 'featured_month', 'is_published', 'is_current',
        'map_image', 'population', 'currency', 'timezone', 'official_language',
        'sunrise_time', 'sunset_time', 'best_transport',
        'highlights', 'tourism_agencies',
    ];

    protected $casts = [
            'featured_month' => 'date',
            'is_published' => 'boolean',
            'is_current' => 'boolean',
            'highlights' => 'array',
            'tourism_agencies' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Le pays à l'honneur actuellement sélectionné par l'admin (sélection unique).
     */
    public static function current(): ?self
    {
        return static::where('is_current', true)->first();
    }

    public function media()
    {
        return $this->hasMany(\App\Models\CountryMedia::class, 'featured_country_id')->orderBy('position');
    }

    public function coverMedia()
    {
        return $this->belongsTo(\App\Models\CountryMedia::class, 'cover_media_id');
    }

    /**
     * URL de la cover : priorité cover_media_id > cover_image.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_media_id && $this->coverMedia) {
            return $this->coverMedia->url;
        }
        if ($this->cover_image) {
            return '/storage/' . $this->cover_image;
        }
        return null;
    }

    /**
     * Catégories utilisées pour la section "Où..." (loger, shopping, manger, etc.).
     */
    public static function placeCategories(): array
    {
        return \App\Models\PlaceCategory::orderBy('position')
            ->get()
            ->mapWithKeys(fn ($c) => [$c->id => ['name' => $c->name, 'slug' => $c->slug, 'icon' => $c->icon]])
            ->all();
    }

    public function countryPlaces()
    {
        return $this->hasMany(\App\Models\CountryPlace::class, 'featured_country_id')
            ->orderBy('position');
    }
}
