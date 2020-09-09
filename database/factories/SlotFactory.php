<?php

namespace Database\Factories;

use App\Models\Slot;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlotFactory extends Factory
{
    protected $model = Slot::class;

    public function definition(): array
    {
        return [
            'abv' => $this->faker->unique()->lexify('???'),
            'name' => 'theme sign'
        ];
    }
}
