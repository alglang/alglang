<?php

namespace Tests\Feature;

use App\Models\Gloss;
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
            'abv' => 'GLS',
            'name' => 'Gloss name',
            /* 'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' */
        ]);

        $response = $this->get($gloss->url);

        $response->assertOk();
        $response->assertViewHas('gloss', $gloss);
        $response->assertSee('GLS');
        $response->assertSee('Gloss name');
        /* $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'); */
    }

    /** @test */
    public function the_gloss_page_has_a_description_if_the_gloss_has_a_description()
    {
        $gloss = factory(Gloss::class)->create([
            'description' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($gloss->url);

        $response->assertOk();
        $response->assertSee('Description');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function the_gloss_page_does_not_have_a_description_if_the_gloss_has_no_description()
    {
        $gloss = factory(Gloss::class)->create([
            'description' => null
        ]);

        $response = $this->get($gloss->url);

        $response->assertOk();
        $response->assertDontSee('Description');
    }
}
