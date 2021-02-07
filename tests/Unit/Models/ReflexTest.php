<?php

namespace Tests\Unit\Models;

use App\Models\ConsonantFeatureSet;
use App\Models\ClusterFeatureSet;
use App\Models\Reflex;
use App\Models\Phoneme;
use App\Models\VowelFeatureSet;
use PHPUnit\Framework\TestCase;

class ReflexTest extends TestCase
{
    /**
     * @dataProvider typeProvider
     * @test
     */
    public function it_has_a_url($typeClass, $typeSlug)
    {
        $reflex = new Reflex([
            'phoneme' => new Phoneme([
                'language_code' => 'PA',
                'slug' => 'x',
                'featureable_type' => $typeClass
            ]),
            'reflex' => new Phoneme(['slug' => 'y'])
        ]);

        $this->assertEquals("/languages/PA/$typeSlug/x/reflexes/y", $reflex->url);
    }

    public function typeProvider(): array
    {
        return [
            [ConsonantFeatureSet::class, 'consonants'],
            [ClusterFeatureSet::class, 'clusters'],
            [VowelFeatureSet::class, 'vowels']
        ];
    }
}
