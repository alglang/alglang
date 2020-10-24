<?php

namespace Database\Factories;

use App\Models\FormGap;
use App\Models\Language;
use App\Models\NominalGap;
use App\Models\NominalStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class NominalGapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NominalGap::class;

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
            'structure_type' => NominalStructure::class,
            'structure_id' => NominalStructure::factory()
        ];
    }
}
