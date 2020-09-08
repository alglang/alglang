<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\NominalForm;
use App\Models\NominalStructure;
use App\Models\NominalParadigm;
use Illuminate\Database\Eloquent\Factories\Factory;

class NominalFormFactory extends Factory
{
    protected $model = NominalForm::class;

    public function definition(): array
    {
        return [
            'shape' => 'V-test',
            'language_code' => Language::factory(),
            'structure_type' => NominalStructure::class,
            'structure_id' => function (array $form) {
                return NominalStructure::factory()->create([
                    'paradigm_id' => NominalParadigm::factory()->create([
                        'language_code' => $form['language_code']
                    ])->id
                ])->id;
            }
        ];
    }
}
