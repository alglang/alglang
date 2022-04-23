<?php

namespace Tests\Feature;

use App\Models\Example;
use App\Models\Form;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_correct_view_for_a_verb_form()
    {
        $example = Example::factory()->verb()->create();
        $response = $this->get($example->url);
        $response->assertOk();
        $response->assertViewIs('examples.show');
    }

    /** @test */
    public function an_example_can_be_viewed()
    {
        $example = Example::factory()->create([
            'shape' => 'foobar',
            'translation' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam',
            'form_id' => Form::factory()->create(['shape' => 'V-bar'])
        ]);

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertViewHas('example', $example);
        $response->assertSee('foobar');
        $response->assertSee('V-bar');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function the_shape_is_formatted_correctly()
    {
        $language = Language::factory()->create(['reconstructed' => true]);
        $example = Example::factory()->create([
            'shape' => 'foobar',
            'form_id' => Form::factory()->create([
                'language_code' => $language
            ])
        ]);

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertSee('<i>*foobar</i>', false);
    }

    /** @test */
    public function an_example_shows_its_phonemic_shape()
    {
        $example = Example::factory()->create([
            'phonemic_shape' => 'phonemes'
        ]);

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertSee('<i>phonemes</i>', false);
    }

    /** @test */
    public function an_example_shows_its_morphemes()
    {
        $language = Language::factory()->create();
        $stem = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => 'foo-'
        ]);
        $form = Form::factory()->create(['language_code' => $language->code]);
        $form->assignMorphemes([$language->vStem, 'bar']);

        $example = Example::factory()->create([
            'form_id' => $form->id,
            'stem_id' => $stem->id
        ]);

        $response = $this->get($example->url);
        $response->assertSee('foo');
        $response->assertSee('bar');
    }

    /** @test */
    public function the_example_parent_is_displayed_if_the_example_has_a_parent()
    {
        $parentLanguage = Language::factory()->create(['name' => 'Superlanguage']);
        $childLanguage = Language::factory()->create(['parent_code' => $parentLanguage->code]);

        $parentExample = Example::factory()->create([
            'shape' => 'V-foo',
            'form_id' => Form::factory()->create([
                'language_code' => $parentLanguage
            ])
        ]);
        $childExample = Example::factory()->create([
            'parent_id' => $parentExample->id,
            'form_id' => Form::factory()->create([
                'language_code' => $childLanguage
            ])
        ]);
        
        $response = $this->get($childExample->url);
        $response->assertOk();
        $response->assertSee('Parent');
        $response->assertSee('V-foo');
        $response->assertSee('Superlanguage');
    }

    /** @test */
    public function the_example_parent_is_not_displayed_if_the_example_has_no_parent()
    {
        $example = Example::factory()->create();

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Parent');
    }

    /** @test */
    public function an_example_shows_its_notes_if_it_has_any()
    {
        $example = Example::factory()->create([
            'notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertSee('Notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function an_example_does_not_show_notes_if_it_has_none()
    {
        $example = Example::factory()->create(['notes' => null]);
        $response = $this->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function an_example_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = User::factory()->create();
        $user->assignRole('contributor');
        $example = Example::factory()->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($example->url);

        $response->assertOk();
        $response->assertSee('Private notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function an_example_does_not_show_private_notes_if_it_has_none()
    {
        $this->withPermissions();

        $user = User::factory()->create();
        $user->assignRole('contributor');
        $example = Example::factory()->create(['private_notes' => null]);

        $response = $this->actingAs($user)->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function an_example_does_not_show_private_notes_if_the_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $example = Example::factory()->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($example->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function sources_appear_if_the_example_has_sources()
    {
        $example = Example::factory()->create();
        $source = Source::factory()->create(['author' => 'Foo Bar']);
        $example->addSource($source);

        $response = $this->get($example->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_example_has_no_sources()
    {
        $example = Example::factory()->create();

        $response = $this->get($example->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }
}
