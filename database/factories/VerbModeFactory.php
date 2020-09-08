<?php

namespace Database\Factories;

use App\Models\VerbMode;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerbModeFactory extends Factory
{
    protected $model = VerbMode::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word
        ];
    }
}
