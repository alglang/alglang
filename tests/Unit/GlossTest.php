<?php

namespace Tests\Unit;

use App\Gloss;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GlossTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_url_property()
    {
        $gloss = factory(Gloss::class)->create(['abv' => 'FOO']);
        $this->assertEquals('/glosses/FOO', $gloss->url);
    }

    /** @test */
    public function null_glosses_have_null_urls()
    {
        $gloss = factory(Gloss::class)->make(['abv' => 'FOO']);
        $this->assertNull($gloss->url);
    }

    /** @test */
    public function abbreviations_must_be_unique()
    {
        factory(Gloss::class)->create(['abv' => 'FOO']);

        $this->expectException(QueryException::class);

        factory(Gloss::class)->create(['abv' => 'FOO']);
    }
}
