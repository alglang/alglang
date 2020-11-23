<?php

namespace Database\Factories;

use App\Models\VowelBackness;
use App\Models\VowelFeatureSet;
use App\Models\VowelHeight;
use App\Models\VowelLength;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VowelFeatureSetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VowelFeatureSet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shape' => $this->faker->unique()->lexify('?????'),
            'height_name' => VowelHeight::factory(),
            'backness_name' => VowelBackness::factory(),
            'length_name' => VowelLength::factory()
        ];
    }
}
