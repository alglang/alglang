<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Slot;
use Illuminate\Database\Eloquent\Factories\Factory;

class MorphemeFactory extends Factory
{
    protected $model = Morpheme::class;

    public function definition()
    {
        return [
            'shape' => '-' . $this->faker->unique()->word . '-',
            'language_code' => Language::factory(),
            'slot_abv' => Slot::factory()
        ];
    }
}
