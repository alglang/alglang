<?php

namespace Tests\Feature;

use App\Example;
use App\Language;
use App\Morpheme;
use App\User;
use App\VerbForm;
use App\VerbClass;
use App\VerbOrder;
use App\VerbMode;
use App\VerbFeature;
use App\VerbStructure;
use App\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_verb_form_can_be_viewed()
    {
        $this->withoutExceptionHandling();
        $language = factory(Language::class)->create(['name' => 'Test Language']);
        $class = factory(VerbClass::class)->create(['abv' => 'TA']);
        $order = factory(VerbOrder::class)->create(['name' => 'Conjunct']);
        $mode = factory(VerbMode::class)->create(['name' => 'Indicative']);
        $subject = factory(VerbFeature::class)->create(['name' => '3s']);
        $verbForm = VerbForm::create([
            'shape' => 'V-test',
            'language_id' => $language->id,

            'structure_id' => factory(VerbStructure::class)->create([
                'class_id' => $class->id,
                'order_id' => $order->id,
                'mode_id' => $mode->id,
                'subject_id' => $subject->id
            ])->id
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Test Language');
        $response->assertSee('V-test');
        $response->assertSee('TA');
        $response->assertSee('Conjunct');
        $response->assertSee('Indicative');
        $response->assertSee('3s');
    }

    /** @test */
    public function a_verb_form_shows_its_morphemes()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create([
            'language_id' => $language->id,
            'shape' => '-morph',
            'gloss' => 'GLS'
        ]);
        $verbForm = factory(VerbForm::class)->create([
            'language_id' => $language->id,
            'morpheme_structure' => "{$morpheme->id}"
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Morphology');
        $response->assertSee('morph');
        $response->assertSee('GLS');
    }

    /** @test */
    public function the_morphology_section_is_not_shown_if_the_verb_form_has_no_morphemes()
    {
        $verbForm = factory(VerbForm::class)->create(['morpheme_structure' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Morphology');
    }

    /** @test */
    public function a_verb_form_shows_its_primary_object()
    {
        $verbForm = factory(VerbForm::class)->create([
            'structure_id' => factory(VerbStructure::class)->create([
                'primary_object_id' => factory(VerbFeature::class)->create(['name' => '2s'])->id
            ])->id
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('→2s');
    }

    /** @test */
    public function a_verb_form_shows_its_secondary_object()
    {
        $verbForm = factory(VerbForm::class)->create([
            'structure_id' => factory(VerbStructure::class)->create([
                'secondary_object_id' => factory(VerbFeature::class)->create(['name' => '1p'])->id
            ])->id
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('+1p');
    }

    /** @test */
    public function a_verb_form_shows_its_historical_notes_if_it_has_them()
    {
        $verbForm = factory(VerbForm::class)->create([
            'historical_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Historical notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_historical_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['historical_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function a_verb_form_shows_its_allomorphy_notes_if_it_has_them()
    {
        $verbForm = factory(VerbForm::class)->create([
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Allomorphy');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_allomorphy_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['allomorphy_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Allomorphy notes');
    }

    /** @test */
    public function a_verb_form_shows_its_usage_notes_if_it_has_them()
    {
        $verbForm = factory(VerbForm::class)->create([
            'usage_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Usage notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_usage_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['usage_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function a_verb_form_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('view private notes');

        $verbForm = factory(VerbForm::class)->create([
            'private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->actingAs($user)->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Private notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_verb_form_does_not_show_private_notes_if_it_does_not_have_them()
    {
        $verbForm = factory(VerbForm::class)->create(['private_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function a_verb_form_does_not_show_private_note_if_the_user_does_not_have_permission()
    {
        $user = factory(User::class)->create();

        $verbForm = factory(VerbForm::class)->create([
            'private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->actingAs($user)->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function sources_appear_if_the_verb_form_has_sources()
    {
        $verbForm = factory(VerbForm::class)->create();
        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $verbForm->addSource($source);

        $response = $this->get($verbForm->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_verb_form_has_no_sources()
    {
        $verbForm = factory(VerbForm::class)->create();

        $response = $this->get($verbForm->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }

    /** @test */
    public function the_form_comes_with_its_example_count()
    {
        $form = factory(VerbForm::class)->create();
        $example = factory(Example::class)->create(['form_id' => $form->id]);

        $response = $this->get($form->url);

        $response->assertOk();
        $response->assertViewHas('verbForm', $form);
        $this->assertEquals(1, $response['verbForm']->examples_count);
    }
}
