<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEngagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('home_content.manage');
    }

    public function rules(): array
    {
        return [
            'icon' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:500'],
            'position' => ['required', 'integer', 'min:0'],
        ];
    }
}
