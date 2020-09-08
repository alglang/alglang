<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\Language;
use App\Models\VerbStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        return [
            'shape' => 'V-test',
            'language_code' => Language::factory(),
            'structure_type' => VerbStructure::class,
            'structure_id' => VerbStructure::factory()
        ];
    }
}
