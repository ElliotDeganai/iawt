<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJourneyStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('home_content.manage');
    }

    public function rules(): array
    {
        return [
            'icon'        => ['required', 'string', 'max:50'],
            'label'       => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'position'    => ['required', 'integer', 'min:0'],
        ];
    }
}
