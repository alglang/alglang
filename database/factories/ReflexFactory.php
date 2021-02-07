<?php

namespace Database\Factories;

use App\Models\Phoneme;
use App\Models\Reflex;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReflexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reflex::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigit,
            'phoneme_id' => Phoneme::factory(),
            'reflex_id' => Phoneme::factory()
        ];
    }
}
