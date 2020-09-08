<?php

namespace Database\Factories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    protected $model = Source::class;

    public function definition(): array
    {
        return [
            'author' => $this->faker->lastName,
            'year' => $this->faker->year,
            'full_citation' => '<p>Joe Brown. 1984. <i>This is the title</i>. Winnipeg: Some Publisher.</p>'
        ];
    }
}
