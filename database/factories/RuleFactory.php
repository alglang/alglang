<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'language_code' => Language::factory(),
            'abv' => $this->faker->unique()->lexify('?????'),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence()
        ];
    }
}
