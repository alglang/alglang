<?php

namespace Tests\Feature;

use App\Gloss;
use App\Language;
use App\Morpheme;
use App\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchMorphemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_morphemes_from_the_database()
    {
        $this->withoutExceptionHandling();

        $language = factory(Language::class)->create(['slug' => 'foo']);
        $slot = factory(Slot::class)->create(['abv' => 'bar']);

        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak',
            'gloss' => 'G1',
            'slot_abv' => $slot->abv,
            'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
            'private_notes' => 'Abcdefghijklmnopqrstuvwxyz'
        ]);

        $response = $this->get("$language->url/morphemes");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [],  // Auto-generated V placeholder
                [],  // Auto-generated N placeholder
                [
                    'shape' => '-ak',
                    'url' => $morpheme->url,
                    'gloss' => 'G1',
                    'slot' => [
                        'abv' => 'bar'
                    ],
                    'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
                    'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
                    'private_notes' => 'Abcdefghijklmnopqrstuvwxyz'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_includes_morpheme_glosses()
    {
        $gloss = factory(Gloss::class)->create([
            'abv' => 'G1',
            'name' => 'gloss 1'
        ]);
        $language = factory(Language::class)->create(['slug' => 'foo']);

        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'gloss' => 'G1.G2',
        ]);

        $response = $this->get("$language->url/morphemes");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [],  // Auto-generated V placeholder
                [],  // Auto-generated N placeholder
                [
                    'gloss' => 'G1.G2',
                    'glosses' => [
                        [
                            'abv' => 'G1',
                            'name' => 'gloss 1',
                            'url' => $gloss->url
                        ],
                        [
                            'abv' => 'G2'
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_include_morpheme_disambiguators()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create(['language_id' => $language->id]);

        $response = $this->get("$language->url/morphemes");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [],  // Auto-generated V placeholder
                [],  // Auto-generated N placeholder
                [
                    'disambiguator' => $morpheme->disambiguator
                ]
            ]
        ]);
    }

    /** @test */
    public function it_fetches_morphemes_10_at_a_time()
    {
        $language = factory(Language::class)->create(['slug' => 'foo']);
        factory(Morpheme::class, 15)->create(['language_id' => $language->id]);

        $response = $this->get("$language->url/morphemes");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(7, 'data');  // +2 for the placeholders
    }

    /** @test */
    public function it_only_retrieves_morphemes_from_the_specified_language()
    {
        $language1 = factory(Language::class)->create(['slug' => 'foo']);
        $language2 = factory(Language::class)->create();

        $morpheme1 = factory(Morpheme::class)->create([
            'shape' => '-ak',
            'language_id' => $language1->id
        ]);
        factory(Morpheme::class, 5)->create(['language_id' => $language2->id]);

        $response = $this->get("$language1->url/morphemes");

        $response->assertOk();
        $response->assertJsonCount(3, 'data');  // +2 for the placeholders
        $response->assertJson([
            'data' => [
                [],  // Auto-generated V placeholder
                [],  // Auto-generated N placeholder
                ['shape' => '-ak']
            ]
        ]);
    }
}
