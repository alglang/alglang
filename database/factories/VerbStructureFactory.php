<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerbStructureFactory extends Factory
{
    protected $model = VerbStructure::class;

    public function definition(): array
    {
        return [
            'class_abv' => VerbClass::factory(),
            'order_name' => VerbOrder::factory(),
            'mode_name' => VerbMode::factory(),
            'subject_name' => Feature::factory()
        ];
    }
}
