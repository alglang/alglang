<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\NominalParadigm;
use App\Models\NominalStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class NominalStructureFactory extends Factory
{
    protected $model = NominalStructure::class;

    public function definition(): array
    {
        return [
            'pronominal_feature_name' => Feature::factory(),
            'nominal_feature_name' => Feature::factory(),
            'paradigm_id' => NominalParadigm::factory()
        ];
    }
}
