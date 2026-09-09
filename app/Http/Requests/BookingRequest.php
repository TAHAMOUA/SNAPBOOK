<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_service' => [
                'required',
                Rule::exists('services', 'id_service')->whereNull('deleted_at'),
            ],
            'id_availability' => [
                'required',
                Rule::exists('availabilities', 'id_availability')->whereNull('deleted_at'),
            ],
            'event_address' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}