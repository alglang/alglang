<?php

namespace Tests\Feature;

use App\Gloss;
use App\Language;
use App\Morpheme;
use App\Slot;
use App\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchMorphemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_language_morphemes()
    {
        $this->withoutExceptionHandling();
        $language = factory(Language::class)->create(['name' => 'Test Language']);
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-ak',
            'slot_abv' => factory(Slot::class)->create(['abv' => 'bar'])->abv
        ]);

        $response = $this->get("/api/morphemes?language_id=$language->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [],  // Auto-generated V placeholder
                [],  // Auto-generated N placeholder
                [
                    'shape' => '-ak',
                    'url' => $morpheme->url,
                    'language' => ['name' => 'Test Language'],
                    'slot' => ['abv' => 'bar']
                ]
            ]
        ]);
    }

    /** @test */
    public function it_fetches_source_morphemes()
    {
        $this->withoutExceptionHandling();
        $source = factory(Source::class)->create();
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => factory(Language::class)->create(['name' => 'Test Language'])->id,
            'shape' => '-ak',
            'slot_abv' => factory(Slot::class)->create(['abv' => 'bar'])->abv
        ]);
        $morpheme->addSource($source);

        $response = $this->get("/api/morphemes?source_id=$source->id");
        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'shape' => '-ak',
                    'url' => $morpheme->url,
                    'language' => ['name' => 'Test Language'],
                    'slot' => ['abv' => 'bar']
                ]
            ]
        ]);
    }

    /** @test */
    public function it_response_with_a_400_if_a_suitable_key_is_not_provided()
    {
        $response = $this->get('/api/morphemes');
        $response->assertStatus(400);
    }

    /** @test */
    public function it_filters_morphemes_by_language()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();

        $morpheme1 = factory(Morpheme::class)->create([
            'shape' => '-ak',
            'language_id' => $language1->id
        ]);
        factory(Morpheme::class, 5)->create(['language_id' => $language2->id]);

        $response = $this->get("/api/morphemes?language_id=$language1->id");

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

    /** @test */
    public function it_filters_morphemes_by_source()
    {
        $source1 = factory(Source::class)->create();
        $source2 = factory(Source::class)->create();
        $morpheme1 = factory(Morpheme::class)->create(['shape' => '-ak']);
        $morpheme2 = factory(Morpheme::class)->create(['shape' => '-bar']);
        $morpheme1->addSource($source1);
        $morpheme2->addSource($source2);

        $response = $this->get("/api/morphemes?source_id=$source1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => '-ak']
            ]
        ]);
    }

    /** @test */
    public function it_filters_morphemes_by_language_and_source()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();
        $source1 = factory(Source::class)->create();
        $source2 = factory(Source::class)->create();
        $morpheme1 = factory(Morpheme::class)->create([
            'language_id' => $language1->id,
            'shape' => '-ak'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_id' => $language1->id,
            'shape' => '-foo'
        ]);
        $morpheme3 = factory(Morpheme::class)->create([
            'language_id' => $language2->id,
            'shape' => '-bar'
        ]);
        $morpheme1->addSource($source1);
        $morpheme2->addSource($source2);
        $morpheme3->addSource($source1);

        $response = $this->get("/api/morphemes?source_id=$source1->id&language_id=$language1->id");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => '-ak']
            ]
        ]);
    }

    /** @test */
    public function it_paginates_language_morphemes()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class, 15)->create(['language_id' => $language->id]);

        $response = $this->get("/api/morphemes?language_id=$language->id");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(7, 'data');  // +2 for the placeholders
    }

    /** @test */
    public function it_paginates_source_morphemes()
    {
        $this->withoutExceptionHandling();
        $source = factory(Source::class)->create();
        $morphemes = factory(Morpheme::class, 11)->create();

        foreach ($morphemes as $morpheme) {
            $morpheme->addSource($source);
        }

        $response = $this->get("/api/morphemes?source_id=$source->id");

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_includes_morpheme_glosses()
    {
        $gloss = factory(Gloss::class)->create(['abv' => 'G1']);
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'gloss' => 'G1.G2',
        ]);

        $response = $this->get("/api/morphemes?language_id=$language->id");

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

        $response = $this->get("/api/morphemes?language_id=$language->id");

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
}
