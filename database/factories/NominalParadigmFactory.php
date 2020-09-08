<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\NominalParadigm;
use App\Models\NominalParadigmType;
use Illuminate\Database\Eloquent\Factories\Factory;

class NominalParadigmFactory extends Factory
{
    protected $model = NominalParadigm::class;

    public function definition()
    {
        return [
            'name' => 'Factory paradigm',
            'paradigm_type_name' => NominalParadigmType::factory(),
            'language_code' => Language::factory()
        ];
    }
}
