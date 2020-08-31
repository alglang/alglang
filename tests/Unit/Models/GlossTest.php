<?php

namespace Tests\Unit\Models;

use App\Models\Gloss;
use Tests\TestCase;

class GlossTest extends TestCase
{
    /** @test */
    public function it_has_a_url_property()
    {
        $gloss = new Gloss(['abv' => 'FOO']);
        $this->assertEquals('/glosses/FOO', $gloss->url);
    }
}
