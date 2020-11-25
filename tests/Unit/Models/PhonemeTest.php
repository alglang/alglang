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
    public function its_ipa_is_its_features_shape_if_it_has_no_override()
    {
        $phoneme = new Phoneme([
            'ipa' => null,
            'features' => new ConsonantFeatureSet([
                'shape' => 'y'
            ])
        ]);

        $this->assertEquals('y', $phoneme->ipa);
    }

    /** @test */
    public function it_can_override_its_default_ipa()
    {
        $phoneme = new Phoneme([
            'ipa' => 'x',
            'features' => new ConsonantFeatureSet([
                'shape' => 'y'
            ])
        ]);

        $this->assertEquals('x', $phoneme->ipa);
    }

    /** @test */
    public function its_ipa_is_an_empty_string_if_its_featureable_type_is_unknown()
    {
        $phoneme = new Phoneme([
            'features' => []
        ]);

        $this->assertEquals('', $phoneme->ipa);
    }

    /** @test */
    public function its_slug_is_its_shape_if_it_has_no_slug()
    {
        $phoneme = new Phoneme([
            'slug' => null,
            'shape' => 'x',
            'features' => new ConsonantFeatureSet([
                'shape' => 'y'
            ])
        ]);

        $this->assertEquals('x', $phoneme->slug);
    }

    /** @test */
    public function its_slug_is_its_ipa_if_it_has_no_shape()
    {
        $phoneme = new Phoneme([
            'slug' => null,
            'shape' => null,
            'features' => new ConsonantFeatureSet([
                'shape' => 'y'
            ])
        ]);

        $this->assertEquals('y', $phoneme->slug);
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

    /** @test */
    public function vowels_have_a_url()
    {
        $phoneme = new Phoneme([
            'shape' => 'x',
            'language_code' => 'TL',
            'featureable_type' => VowelFeatureSet::class
        ]);
        $this->assertEquals('/languages/TL/vowels/x', $phoneme->url);
    }

    /** @test */
    public function consonants_have_a_url()
    {
        $phoneme = new Phoneme([
            'shape' => 'x',
            'language_code' => 'TL',
            'featureable_type' => ConsonantFeatureSet::class
        ]);
        $this->assertEquals('/languages/TL/consonants/x', $phoneme->url);
    }

    /** @test */
    public function clusters_have_a_url()
    {
        $phoneme = new Phoneme([
            'shape' => 'x',
            'language_code' => 'TL',
            'featureable_type' => ClusterFeatureSet::class
        ]);
        $this->assertEquals('/languages/TL/clusters/x', $phoneme->url);
    }

    /** @test */
    public function null_phonemes_do_not_have_a_url()
    {
        $phoneme = new Phoneme(['shape' => '∅']);
        $this->assertEquals('', $phoneme->url);
    }

    /** @test */
    public function it_is_an_archiphoneme_if_its_features_are_marked_as_being_an_archiphoneme(): void
    {
        $phoneme = new Phoneme([
            'featureable_type' => ConsonantFeatureSet::class,
            'features' => new ConsonantFeatureSet(['is_archiphoneme' => 1])
        ]);
        $this->assertTrue($phoneme->is_archiphoneme);
    }

    /** @test */
    public function it_is_not_an_archiphoneme_if_its_features_are_marked_as_not_being_an_archiphoneme(): void
    {
        $phoneme = new Phoneme([
            'featureable_type' => ConsonantFeatureSet::class,
            'features' => new ConsonantFeatureSet(['is_archiphoneme' => false])
        ]);
        $this->assertFalse($phoneme->is_archiphoneme);
    }

    /** @test */
    public function it_is_not_an_archiphoneme_if_its_features_do_not_have_an_archiphoneme_field(): void
    {
        $phoneme = new Phoneme([
            'featureable_type' => ClusterFeatureSet::class,
            'features' => new ClusterFeatureSet()
        ]);
        $this->assertFalse($phoneme->is_archiphoneme);
    }

    /** @test */
    public function it_is_not_an_archiphoneme_if_it_has_no_features(): void
    {
        $phoneme = new Phoneme([
            'featureable_type' => null,
            'features' => null
        ]);
        $this->assertFalse($phoneme->is_archiphoneme);
    }
}
