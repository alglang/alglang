<?php

namespace Tests\Unit\Models;

use App\Models\Phoneme;
use App\Models\VowelFeatureSet;
use App\Models\ClusterFeatureSet;
use App\Models\ConsonantFeatureSet;
use PHPUnit\Framework\TestCase;

class PhonemeTest extends TestCase
{
    /** @test */
    public function its_slug_is_its_shape_if_it_has_no_slug()
    {
        $phoneme = new Phoneme(['slug' => null, 'shape' => 'x', 'ipa' => 'y']);
        $this->assertEquals('x', $phoneme->slug);
    }

    /** @test */
    public function its_slug_is_its_ipa_if_it_has_no_shape()
    {
        $phoneme = new Phoneme(['slug' => null, 'shape' => null, 'ipa' => 'y']);
        $this->assertEquals('y', $phoneme->slug);
    }

    /** @test */
    public function it_has_a_url()
    {
        $phoneme = new Phoneme(['shape' => 'x', 'language_code' => 'TL']);
        $this->assertEquals('/languages/TL/phonemes/x', $phoneme->url);
    }

    /** @test */
    public function its_type_is_vowel_if_it_has_a_vowel_feature_set()
    {
        $vowel = new Phoneme(['featureable_type' => VowelFeatureSet::class]);
        $this->assertEquals('vowel', $vowel->type);
    }

    /** @test */
    public function its_type_is_consonant_if_it_has_a_consonant_feature_set()
    {
        $consonant = new Phoneme(['featureable_type' => ConsonantFeatureSet::class]);
        $this->assertEquals('consonant', $consonant->type);
    }

    /** @test */
    public function its_type_is_cluster_if_it_has_a_cluster_feature_set()
    {
        $cluster = new Phoneme(['featureable_type' => ClusterFeatureSet::class]);
        $this->assertEquals('cluster', $cluster->type);
    }

    /** @test */
    public function its_type_is_an_empty_string_if_it_does_not_recognize_its_feature_set()
    {
        $cluster = new Phoneme();
        $this->assertEquals('', $cluster->type);
    }
}
