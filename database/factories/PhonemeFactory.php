<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Phoneme;
use App\Models\VowelFeatureSet;
use App\Models\ClusterFeatureSet;
use App\Models\ConsonantFeatureSet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PhonemeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Phoneme::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement([VowelFeatureSet::class, ConsonantFeatureSet::class]);

        return [
            'shape' => $this->faker->unique()->lexify('??'),
            'language_code' => Language::factory(),
            'featureable_type' => $type,
            'featureable_id' => $type::factory()
        ];
    }

    public function vowel(array $attributes = []): self
    {
        return $this->state([
            'featureable_type' => VowelFeatureSet::class,
            'featureable_id' => VowelFeatureSet::factory()->state($attributes)
        ]);
    }

    public function consonant(array $attributes = []): self
    {
        return $this->state([
            'featureable_type' => ConsonantFeatureSet::class,
            'featureable_id' => ConsonantFeatureSet::factory()->state($attributes)
        ]);
    }

    public function cluster(array $attributes = []): self
    {
        return $this->state([
            'featureable_type' => ClusterFeatureSet::class,
            'featureable_id' => ClusterFeatureSet::factory()->state($attributes)
        ]);
    }

    public function null(): self
    {
        return $this->state([
            'shape' => '∅',
            'featureable_type' => null,
            'featureable_id' => null
        ]);
    }
}
