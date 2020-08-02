<?php

namespace Tests\Feature;

use App\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sources_can_be_viewed()
    {
        $this->withoutExceptionHandling();
        $source = factory(Source::class)->create([
            'author' => 'Foo bar',
            'year' => 1234,
            'full_citation' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>'
        ]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $response->assertSee('Foo bar');
        $response->assertSee('1234');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr');
    }
}
