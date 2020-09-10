<?php

namespace Tests\Feature;

use App\Models\Example;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\NominalGap;
use App\Models\NominalParadigm;
use App\Models\Rule;
use App\Models\Source;
use App\Models\VerbForm;
use App\Models\VerbGap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sources_can_be_viewed()
    {
        $source = Source::factory()->create([
            'author' => 'Foo bar',
            'year' => 1234
        ]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $response->assertSee('Foo bar');
        $response->assertSee('1234');
    }

    /** @test */
    public function a_source_shows_its_full_citation_if_it_has_one()
    {
        $source = Source::factory()->create([
            'full_citation' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertSee('Full citation');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_source_does_not_show_a_full_citation_if_it_has_none()
    {
        $source = Source::factory()->create(['full_citation' => null]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertDontSee('Full citation');
    }

    /** @test */
    public function a_source_shows_its_website_if_it_has_one()
    {
        $source = Source::factory()->create([
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
        $source = Source::factory()->create(['website' => null]);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertDontSee('Website');
    }

    /** @test */
    public function a_source_shows_its_summary()
    {
        $source = Source::factory()->create(['summary' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertSee('Summary');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_source_does_not_show_a_summary_if_it_has_none()
    {
        $source = Source::factory()->create(['summary' => null]);
        
        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertDontSee('Summary');
    }

    /** @test */
    public function a_source_shows_its_notes()
    {
        $source = Source::factory()->create(['notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertSee('Notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_source_does_not_show_a_notes_if_it_has_none()
    {
        $source = Source::factory()->create(['notes' => null]);
        
        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function the_source_comes_with_its_morpheme_count()
    {
        $source = Source::factory()->create();
        $morpheme = Morpheme::factory()->create();
        $morpheme->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->morphemes_count);
    }

    /** @test */
    public function the_source_comes_with_its_verb_form_count()
    {
        $source = Source::factory()->create();
        $verbForm = VerbForm::factory()->create()->addSource($source);
        $verbGap = VerbGap::factory()->create()->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(2, $response['source']->verb_forms_and_gaps_count);
    }

    /** @test */
    public function the_source_comes_with_its_nominal_form_count()
    {
        $source = Source::factory()->create();
        $nominalForm = NominalForm::factory()->create()->addSource($source);
        $nominalGap = NominalGap::factory()->create()->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(2, $response['source']->nominal_forms_and_gaps_count);
    }

    /** @test */
    public function the_source_comes_with_its_nominal_paradigm_count()
    {
        $source = Source::factory()->create();
        $paradigm = NominalParadigm::factory()->create()->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->nominal_paradigms_count);
    }

    /** @test */
    public function the_source_comes_with_its_example_count()
    {
        $source = Source::factory()->create();
        $example = Example::factory()->create();
        $example->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->examples_count);
    }

    /** @test */
    public function the_source_comes_with_its_rule_count()
    {
        $source = Source::factory()->create();
        $rule = Rule::factory()->create()->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->rules_count);
    }

    /** @test */
    public function the_source_comes_with_its_phoneme_count()
    {
        $source = Source::factory()->hasPhonemes(1)->create();
        $phoneme = Phoneme::factory()->create()->addSource($source);

        $response = $this->get($source->url);

        $response->assertOk();
        $response->assertViewHas('source', $source);
        $this->assertEquals(1, $response['source']->phonemes_count);
    }
}
