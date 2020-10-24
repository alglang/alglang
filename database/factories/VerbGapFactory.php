<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\VerbGap;
use App\Models\VerbStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerbGapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VerbGap::class;

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
}
