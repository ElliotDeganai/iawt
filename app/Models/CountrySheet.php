<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountrySheet extends Model
{
    protected $fillable = [
        'country_name', 'flag_emoji', 'title', 'intro', 'warning',
        'actors', 'organism_name', 'organism_full', 'organism_desc',
        'organism_address', 'organism_hours', 'organism_website',
        'documents', 'individual_steps', 'company_steps',
        'fees', 'sector_auth', 'is_published',
    ];

    protected $casts = [
        'actors'           => 'array',
        'documents'        => 'array',
        'individual_steps' => 'array',
        'company_steps'    => 'array',
        'is_published'     => 'boolean',
    ];
}
