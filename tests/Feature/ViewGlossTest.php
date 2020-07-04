<?php

namespace Tests\Feature;

use App\Gloss;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewGlossTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function glosses_can_be_viewed()
    {
        $gloss = factory(Gloss::class)->create([
            'abv' => 'FOO',
            'name' => 'Foo bar',
            'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($gloss->url);

        $response->assertOk();
        $response->assertSee('FOO');
        $response->assertSee('Foo bar');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }
}
