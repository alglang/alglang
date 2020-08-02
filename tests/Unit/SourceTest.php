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

        $this->assertEquals($source->url, '/sources/foo-bar-1234');
    }

    /** @test */
    public function it_has_a_short_citation()
    {
        $source = factory(Source::class)->create([
            'author' => 'Foo Bar',
            'year' => 1234
        ]);

        $this->assertEquals($source->short_citation, 'Foo Bar 1234');
    }
}
