<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerbFormSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'languages' => 'array',
            'structures' => 'required',
            'structures.*.modes' => 'required|array|size:1',
            'structures.*.classes' => 'required|array|size:1',
            'structures.*.orders' => 'required|array|size:1',
            'structures.*.subject_persons' => 'required|array|size:1',
            'structures.*.subject_numbers' => 'nullable|array|size:1',
            'structures.*.subject_obviative_codes' => 'nullable|array|size:1',
            'structures.*.primary_object_persons' => 'nullable|array|size:1',
            'structures.*.primary_object_numbers' => 'nullable|array|size:1',
            'structures.*.primary_object_obviative_codes' => 'nullable|array|size:1',
            'structures.*.secondary_object_persons' => 'nullable|array|size:1',
            'structures.*.secondary_object_numbers' => 'nullable|array|size:1',
            'structures.*.secondary_object_obviative_codes' => 'nullable|array|size:1',
        ];
    }
}
