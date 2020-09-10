<?php

namespace Tests\Unit\Models;

use App\Models\Phoneme;
use App\Models\VowelFeatureSet;
use App\Models\ConsonantFeatureSet;
use Tests\TestCase;

class PhonemeTest extends TestCase
{
    /** @test */
    public function its_slug_is_its_shape_if_it_has_no_slug()
    {
        $phoneme = Phoneme::factory()->make(['slug' => null, 'shape' => 'x', 'ipa' => 'y']);
        $this->assertEquals('x', $phoneme->slug);
    }

    /** @test */
    public function its_slug_is_its_ipa_if_it_has_no_shape()
    {
        $phoneme = Phoneme::factory()->make(['slug' => null, 'shape' => null, 'ipa' => 'y']);
        $this->assertEquals('y', $phoneme->slug);
    }

    /** @test */
    public function it_has_a_url()
    {
        $phoneme = Phoneme::factory()->forLanguage(['code' => 'TL'])->make(['shape' => 'x']);
        $this->assertEquals('/languages/TL/phonemes/x', $phoneme->url);
    }

    /** @test */
    public function its_type_is_vowel_if_it_has_a_vowel_feature_set()
    {
        $vowel = Phoneme::factory()->make(['featureable_type' => VowelFeatureSet::class]);
        $this->assertEquals('vowel', $vowel->type);
    }

    /** @test */
    public function its_type_is_consonant_if_it_has_a_consonant_feature_set()
    {
        $consonant = Phoneme::factory()->make(['featureable_type' => ConsonantFeatureSet::class]);
        $this->assertEquals('consonant', $consonant->type);
    }
}
