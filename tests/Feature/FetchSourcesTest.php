<?php

namespace Tests\Feature;

use App\Language;
use App\Morpheme;
use App\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchSourcesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_sources()
    {
        $source1 = factory(Source::class)->create(['author' => 'Jane Doe']);
        $source2 = factory(Source::class)->create(['author' => 'John Doe']);

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
        $source1 = factory(Source::class)->create(['author' => 'Jane Doe']);
        $source2 = factory(Source::class)->create(['author' => 'John Doe']);
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();
        $morpheme1 = factory(Morpheme::class)->create(['language_id' => $language1->id]);
        $morpheme2 = factory(Morpheme::class)->create(['language_id' => $language2->id]);
        $morpheme1->addSource($source1);
        $morpheme2->addSource($source2);

        $response = $this->get("/api/sources?language_id=$language1->id");

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
        $source = factory(Source::class)->create();

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
        $source = factory(Source::class)->create();

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
        factory(Source::class, 11)->create();

        $response = $this->get('/api/sources');
        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $nextResponse = $this->get($response->decodeResponseJson()['links']['next']);
        $nextResponse->assertOk();
        $nextResponse->assertJsonCount(1, 'data');
    }
}
