<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $imageRule = 'sometimes';

        if ($this->isMethod('POST')) {
            $imageRule = 'required';
        }

        return [
            'image' => [$imageRule, 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.uploaded' => 'The image must not be larger than 5 MB.',
        ];
    }
}
