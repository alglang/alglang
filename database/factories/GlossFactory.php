<?php

namespace Database\Factories;

use App\Models\Gloss;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlossFactory extends Factory
{
    protected $model = Gloss::class;

    public function definition(): array
    {
        return [
            'abv' => 'DGN',
            'name' => 'Dummy gloss name'
        ];
    }
}
