<?php

namespace Database\Factories;

use App\Models\ConsonantFeatureSet;
use App\Models\ConsonantManner;
use App\Models\ConsonantPlace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConsonantFeatureSetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsonantFeatureSet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'place_name' => ConsonantPlace::factory(),
            'manner_name' => ConsonantManner::factory()
        ];
    }
}
