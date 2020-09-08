<?php

namespace Database\Factories;

use App\Models\VerbOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerbOrderFactory extends Factory
{
    protected $model = VerbOrder::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word
        ];
    }
}
