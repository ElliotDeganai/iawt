<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('events.manage');
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:191'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'url'          => ['nullable', 'url', 'max:500'],
            'date'         => ['required', 'date'],
            'time'         => ['nullable', 'date_format:H:i'],
            'is_featured'  => ['boolean'],
            'is_published' => ['boolean'],
        ];
    }
}
