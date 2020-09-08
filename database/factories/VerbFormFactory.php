<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\VerbForm;
use App\Models\VerbStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerbFormFactory extends Factory
{
    protected $model = VerbForm::class;

    public function definition(): array
    {
        return [
            'shape' => 'V-factory',
            'language_code' => Language::factory(),
            'structure_type' => VerbStructure::class,
            'structure_id' => VerbStructure::factory()
        ];
    }
}
