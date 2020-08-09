<?php

namespace Tests\Feature;

use App\Example;
use App\Morpheme;
use App\Source;
use App\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sources_can_be_viewed()
    {
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

    /** @test */
    public function a_source_shows_its_website_if_it_has_one()
    {
        $source = factory(Source::class)->create([
            'website' => 'https://google.ca'
        ]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertSee('Website');
        $response->assertSee('https://google.ca');
    }

    /** @test */
    public function a_source_does_not_show_a_website_if_it_has_none()
    {
        $source = factory(Source::class)->create(['website' => null]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertDontSee('Website');
    }

    /** @test */
    public function the_source_comes_with_its_morpheme_count()
    {
        $source = factory(Source::class)->create();
        $morpheme = factory(Morpheme::class)->create();
        $morpheme->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->morphemes_count);
    }

    /** @test */
    public function the_source_comes_with_its_verb_form_count()
    {
        $source = factory(Source::class)->create();
        $verbForm = factory(VerbForm::class)->create();
        $verbForm->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->verb_forms_count);
    }

    /** @test */
    public function the_source_comes_with_its_example_count()
    {
        $source = factory(Source::class)->create();
        $example = factory(Example::class)->create();
        $example->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->examples_count);
    }
}
