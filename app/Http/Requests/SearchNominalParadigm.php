<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchNominalParadigm extends FormRequest
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
            'languages' => 'required_without:paradigm_types|array',
            'paradigm_types' => 'required_without:languages|array'
        ];
    }

    public function messages()
    {
        return [
            'languages.required_without' => 'Please select at least one language or paradigm.',
            'paradigm_types.required_without' => 'Please select at least one language or paradigm.'
        ];
    }
}
