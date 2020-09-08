<?php

namespace Database\Factories;

use App\Models\NominalParadigmType;
use Illuminate\Database\Eloquent\Factories\Factory;

class NominalParadigmTypeFactory extends Factory
{
    protected $model = NominalParadigmType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'has_pronominal_feature' => true,
            'has_nominal_feature' => true
        ];
    }
}
