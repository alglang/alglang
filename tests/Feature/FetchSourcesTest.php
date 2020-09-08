<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchSourcesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_sources()
    {
        $source1 = Source::factory()->create(['author' => 'Jane Doe']);
        $source2 = Source::factory()->create(['author' => 'John Doe']);

        $response = $this->get('/api/sources');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJson([
            'data' => [
                ['author' => 'Jane Doe'],
                ['author' => 'John Doe']
            ]
        ]);
    }

    /** @test */
    public function it_filters_sources_by_language()
    {
        $this->withoutExceptionHandling();
        $source1 = Source::factory()->create(['author' => 'Jane Doe']);
        $source2 = Source::factory()->create(['author' => 'John Doe']);
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();
        $morpheme1 = Morpheme::factory()->create(['language_code' => $language1->code]);
        $morpheme2 = Morpheme::factory()->create(['language_code' => $language2->code]);
        $morpheme1->addSource($source1);
        $morpheme2->addSource($source2);

        $response = $this->get("/api/sources?language=$language1->code");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                ['author' => 'Jane Doe']
            ]
        ]);
    }

    /** @test */
    public function it_includes_short_citations()
    {
        $source = Source::factory()->create();

        $response = $this->get('/api/sources');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['short_citation' => $source->short_citation]
            ]
        ]);
    }

    /** @test */
    public function it_includes_urls()
    {
        $source = Source::factory()->create();

        $response = $this->get('/api/sources');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['url' => $source->url]
            ]
        ]);
    }

    /** @test */
    public function it_paginates_sources()
    {
        Source::factory()->count(3)->create();

        $response = $this->get('/api/sources?per_page=2');
        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_sorts_sources_by_author_then_year()
    {
        $source1 = Source::factory()->create([
            'author' => 'Beta',
            'year' => 10
        ]);
        $source2 = Source::factory()->create([
            'author' => 'Alpha',
            'year' => 11
        ]);
        $source3 = Source::factory()->create([
            'author' => 'Alpha',
            'year' => 12
        ]);

        $response = $this->get('/api/sources');

        $response->assertJson([
            'data' => [
                ['id' => $source3->id],
                ['id' => $source2->id],
                ['id' => $source1->id]
            ]
        ]);
    }
}
