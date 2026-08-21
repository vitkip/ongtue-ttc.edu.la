<?php

namespace App\Http\Requests;

use App\Support\ApplicationOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(array_keys(ApplicationOptions::genders()))],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'village' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', Rule::in(array_keys(ApplicationOptions::provinces()))],

            'previous_school' => ['nullable', 'string', 'max:255'],
            'accommodation_type' => ['required', Rule::in(array_keys(ApplicationOptions::accommodationTypes()))],

            'major_id' => ['required', 'integer', 'exists:courses,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],

            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
            'certificate' => ['nullable', 'mimes:jpg,jpeg,png,gif,pdf', 'max:10240'],
            'transcript' => ['nullable', 'mimes:jpg,jpeg,png,gif,pdf', 'max:10240'],
        ];
    }
}
