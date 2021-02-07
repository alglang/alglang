<?php

namespace Database\Factories;

use App\Models\ClusterFeatureSet;
use App\Models\ConsonantFeatureSet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClusterFeatureSetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClusterFeatureSet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_segment_id' => ConsonantFeatureSet::factory(),
            'second_segment_id' => ConsonantFeatureSet::factory()
        ];
    }
}
