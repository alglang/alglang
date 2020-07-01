<?php

namespace Tests\Feature;

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
        $language = factory(Language::class)->create(['slug' => 'foo']);
        $slot = factory(Slot::class)->create(['abv' => 'bar']);

        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak',
            'slot_id' => $slot->id,
            'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
            'private_notes' => 'Abcdefghijklmnopqrstuvwxyz'
        ]);

        $response = $this->get("$language->url/morphemes");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => '-ak',
                    'url' => $morpheme->url,
                    'slot' => [
                        'id' => $slot->id,
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
    public function it_fetches_morphemes_10_at_a_time()
    {
        $language = factory(Language::class)->create(['slug' => 'foo']);
        factory(Morpheme::class, 15)->create(['language_id' => $language->id]);

        $response = $this->get("$language->url/morphemes");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['next_page_url']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(5, 'data');
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
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => '-ak']
            ]
        ]);
    }
}
