<?php

namespace Database\Factories;

use App\Models\ClusterFeatureSet;
use App\Models\Phoneme;
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
            'first_segment_id' => Phoneme::factory()->consonant(),
            'second_segment_id' => Phoneme::factory()->consonant()
        ];
    }
}
