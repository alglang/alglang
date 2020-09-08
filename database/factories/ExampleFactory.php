<?php

namespace Database\Factories;

use App\Models\Example;
use App\Models\Form;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\VerbForm;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExampleFactory extends Factory
{
    protected $model = Example::class;

    public function definition(): array
    {
        return [
            'shape' => 'exampleshape',
            'stem_id' => Morpheme::factory(),
            'form_id' => Form::factory(),
            'translation' => '<p>factory translation</p>'
        ];
    }

    public function verb()
    {
        return $this->state([
            'form_id' => VerbForm::factory()
        ]);
    }

    public function nominal()
    {
        return $this->state([
            'form_id' => NominalForm::factory()
        ]);
    }
}
