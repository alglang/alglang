<?php

namespace Database\Factories;

use App\Models\FormGap;
use App\Models\Language;
use App\Models\NominalStructure;
use App\Models\VerbStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormGapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FormGap::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => rand(),
            'language_code' => Language::factory(),
            'structure_type' => VerbStructure::class,
            'structure_id' => VerbStructure::factory()
        ];
    }

    public function verb(): self
    {
        return $this->state([
            'structure_type' => VerbStructure::class,
            'structure_id' => VerbStructure::factory()
        ]);
    }

    public function nominal(): self
    {
        return $this->state([
            'structure_type' => NominalStructure::class,
            'structure_id' => NominalStructure::factory()
        ]);
    }
}
