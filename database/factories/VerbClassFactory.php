<?php

namespace Database\Factories;

use App\Models\VerbClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerbClassFactory extends Factory
{
    protected $model = VerbClass::class;

    public function definition(): array
    {
        return [
            'abv' => $this->faker->unique()->lexify('??')
        ];
    }
}
