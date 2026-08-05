<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeaturedCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('countries.manage');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:191', 'alpha_dash', Rule::unique('featured_countries', 'slug')->ignore($this->route('country'))],
            'flag_emoji' => ['nullable', 'string', 'max:10'],
            'flag_image' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:2048'],
            'tags' => ['nullable', 'string', 'max:191'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'map_image' => ['nullable', 'image', 'max:4096'],
            'featured_month' => ['required', 'date'],
            'is_published' => ['boolean'],

            'population' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'official_language' => ['nullable', 'string', 'max:100'],
            'sunrise_time' => ['nullable', 'string', 'max:20'],
            'sunset_time' => ['nullable', 'string', 'max:20'],
            'best_transport' => ['nullable', 'string', 'max:191'],

            'highlights' => ['nullable', 'array', 'max:12'],
            'highlights.*.name' => ['required_with:highlights', 'string', 'max:191'],
            'highlights.*.description' => ['nullable', 'string', 'max:500'],

            'tourism_agencies' => ['nullable', 'array'],
            'tourism_agencies.*.name' => ['required_with:tourism_agencies', 'string', 'max:191'],
            'tourism_agencies.*.website' => ['nullable', 'string', 'max:191'],
            'tourism_agencies.*.email' => ['nullable', 'email', 'max:191'],
            'tourism_agencies.*.phone' => ['nullable', 'string', 'max:50'],

            'country_places' => ['nullable', 'array'],
            'country_places.*' => ['array'],
            'country_places.*.*.name' => ['required', 'string', 'max:191'],
            'country_places.*.*.description' => ['nullable', 'string', 'max:500'],
            'country_places.*.*.link' => ['nullable', 'string', 'max:191'],
        ];
    }
}
