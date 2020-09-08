<?php

namespace Tests\Feature;

use App\Models\Gloss;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Slot;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchMorphemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_language_morphemes()
    {
        $language = Language::factory()->create(['name' => 'Test Language']);
        $morpheme = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-ak',
            'slot_abv' => Slot::factory()->create(['abv' => 'bar'])->abv
        ]);

        $response = $this->get("/api/morphemes?language=$language->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
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
    public function it_fetches_source_morphemes()
    {
        $source = Source::factory()->create();
        $morpheme = Morpheme::factory()->create([
            'language_code' => Language::factory()->create(['name' => 'Test Language'])->code,
            'shape' => '-ak',
            'slot_abv' => Slot::factory()->create(['abv' => 'bar'])->abv
        ]);
        $morpheme->addSource($source);

        $response = $this->get("/api/morphemes?source_id=$source->id");
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
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
    public function it_does_not_include_placeholder_morphemes()
    {
        $language = Language::factory()->create();
        $this->assertDatabaseCount('morphemes', 2);  // Verify that the placeholder morphemes were generated

        $response = $this->get("/api/morphemes?language=$language->code");
        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_responds_with_a_400_if_a_suitable_key_is_not_provided()
    {
        $response = $this->get('/api/morphemes');
        $response->assertStatus(400);
    }

    /** @test */
    public function it_filters_morphemes_by_language()
    {
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();

        $morpheme1 = Morpheme::factory()->create([
            'shape' => '-ak',
            'language_code' => $language1->code
        ]);
        Morpheme::factory()->count(5)->create(['language_code' => $language2->code]);

        $response = $this->get("/api/morphemes?language=$language1->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => '-ak']
            ]
        ]);
    }

    /** @test */
    public function it_filters_morphemes_by_source()
    {
        $source1 = Source::factory()->create();
        $source2 = Source::factory()->create();
        $morpheme1 = Morpheme::factory()->create(['shape' => '-ak']);
        $morpheme2 = Morpheme::factory()->create(['shape' => '-bar']);
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
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();
        $source1 = Source::factory()->create();
        $source2 = Source::factory()->create();
        $morpheme1 = Morpheme::factory()->create([
            'language_code' => $language1->code,
            'shape' => '-ak'
        ]);
        $morpheme2 = Morpheme::factory()->create([
            'language_code' => $language1->code,
            'shape' => '-foo'
        ]);
        $morpheme3 = Morpheme::factory()->create([
            'language_code' => $language2->code,
            'shape' => '-bar'
        ]);
        $morpheme1->addSource($source1);
        $morpheme2->addSource($source2);
        $morpheme3->addSource($source1);

        $response = $this->get("/api/morphemes?source_id=$source1->id&language=$language1->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['shape' => '-ak']
            ]
        ]);
    }

    /** @test */
    public function it_paginates_morphemes()
    {
        $language = Language::factory()->create();
        Morpheme::factory()->count(3)->create(['language_code' => $language->code]);

        $response = $this->get("/api/morphemes?language=$language->code&per_page=2");

        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_includes_morpheme_formatted_shape()
    {
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create(['language_code' => $language]);

        $response = $this->get("/api/morphemes?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['formatted_shape' => $morpheme->formatted_shape]
            ]
        ]);
    }

    /** @test */
    public function it_includes_morpheme_glosses()
    {
        $gloss = Gloss::factory()->create(['abv' => 'G1']);
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create([
            'language_code' => $language->code,
            'gloss' => 'G1.G2',
        ]);

        $response = $this->get("/api/morphemes?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
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
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create(['language_code' => $language->code]);

        $response = $this->get("/api/morphemes?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'disambiguator' => $morpheme->disambiguator
                ]
            ]
        ]);
    }

    /** @todo when mysql test environment is set up */
    public function morphemes_are_sorted_alphabetically_by_shape()
    {
        $language = Language::factory()->create();
        Morpheme::factory()->create(['language' => $language->code, 'shape' => 'c-']);
        Morpheme::factory()->create(['language' => $language->code, 'shape' => 'a-']);
        Morpheme::factory()->create(['language' => $language->code, 'shape' => 'b-']);

        $response = $this->get("/api/morphemes?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['shape' => 'a-'],
                ['shape' => 'b-'],
                ['shape' => 'c-'],
            ]
        ]);
    }

    /** @test */
    public function morphemes_are_sorted_ignoring_hyphens()
    {
        $language = Language::factory()->create();
        Morpheme::factory()->create(['language_code' => $language->code, 'shape' => 'a-']);
        Morpheme::factory()->create(['language_code' => $language->code, 'shape' => '-b']);

        $response = $this->get("/api/morphemes?language=$language->code");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['shape' => 'a-'],
                ['shape' => '-b']
            ]
        ]);
    }
}
