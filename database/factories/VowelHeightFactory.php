<?php

namespace Database\Factories;

use App\Models\VowelHeight;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VowelHeightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VowelHeight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word
        ];
    }
}
