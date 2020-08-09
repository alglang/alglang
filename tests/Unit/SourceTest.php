<?php

namespace Tests\Unit;

use App\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url()
    {
        $source = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $this->assertEquals('/sources/foo-bar-1234', $source->url);
    }

    /** @test */
    public function its_url_includes_its_disambiguator_when_necessary()
    {
        $sourceA = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $sourceB = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $this->assertEquals('/sources/foo-bar-1234-b', $sourceB->fresh()->url);
        $this->assertEquals('/sources/foo-bar-1234-a', $sourceA->fresh()->url);
    }

    /** @test */
    public function it_has_a_disambiguation_letter()
    {
        $sourceA = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $sourceB = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $this->assertEquals('a', $sourceA->fresh()->disambiguation_letter);
        $this->assertEquals('b', $sourceB->fresh()->disambiguation_letter);
    }

    /** @test */
    public function it_has_a_short_citation()
    {
        $source = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $this->assertEquals('Foo Bar 1234', $source->short_citation);
    }

    /** @test */
    public function its_short_citation_includes_its_disambiguation_letter()
    {
        $sourceA = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $sourceB = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $this->assertEquals('Foo Bar 1234a', $sourceA->fresh()->short_citation);
        $this->assertEquals('Foo Bar 1234b', $sourceB->fresh()->short_citation);
    }

    /** @test */
    public function it_orders_queries_by_author_then_year()
    {
        $source1 = factory(Source::class)->create([
            'author' => 'Beta',
            'year' => 10
        ]);
        $source2 = factory(Source::class)->create([
            'author' => 'Alpha',
            'year' => 11
        ]);
        $source3 = factory(Source::class)->create([
            'author' => 'Alpha',
            'year' => 12
        ]);

        $sources = Source::all();

        $targets = [$source3->id, $source2->id, $source1->id];
        $this->assertEquals($targets, $sources->pluck('id')->toArray());
    }
}
