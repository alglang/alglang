<?php

namespace Tests\Feature;

use App\Models\Example;
use App\Models\Feature;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Source;
use App\Models\User;
use App\Models\VerbClass;
use App\Models\VerbForm;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewVerbFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_verb_form_can_be_viewed()
    {
        $language = Language::factory()->create(['name' => 'Test Language']);
        $subject = Feature::factory()->create(['name' => '3s']);
        $verbForm = VerbForm::factory()->create([
            'language_code' => $language->code,

            'structure_id' => VerbStructure::factory()->create([
                'class_abv' => VerbClass::factory()->create(['abv' => 'TA']),
                'order_name' => VerbOrder::factory()->create(['name' => 'Conjunct']),
                'mode_name' => VerbMode::factory()->create(['name' => 'Indicative']),
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Test Language');
        $response->assertSee('TA');
        $response->assertSee('Conjunct');
        $response->assertSee('Indicative');
    }

    /** @test */
    public function the_shape_is_formatted_correctly()
    {
        $language = Language::factory()->create(['reconstructed' => true]);
        $verbForm = VerbForm::factory()->create([
            'shape' => 'V-ak',
            'language_code' => $language
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('<i>*<span class="not-italic">V</span>-ak</i>', false);
    }

    /** @test */
    public function it_shows_its_features()
    {
        $language = Language::factory()->create(['reconstructed' => true]);
        $verbForm = VerbForm::factory()->create([
            'language_code' => $language,
            'structure_id' => VerbStructure::factory()->create([
                'subject_name' => Feature::factory()->create(['name' => '1s']),
                'primary_object_name' => Feature::factory()->create(['name' => '2d']),
                'secondary_object_name' => Feature::factory()->create(['name' => '3p']),
                'head' => 'primary object'
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('1s→<u>2d</u>+3p', false);
    }

    /** @test */
    public function a_verb_form_shows_that_it_is_negative_if_it_is_negative()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_negative' => true
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Negative');
    }

    /** @test */
    public function a_verb_form_does_not_show_that_it_is_negative_if_it_is_not_negative()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_negative' => false
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Negative');
    }

    /** @test */
    public function a_verb_form_shows_that_it_is_diminutive_if_it_is_diminutive()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_diminutive' => true
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Diminutive');
    }

    /** @test */
    public function a_verb_form_shows_that_it_is_absolute_if_it_is_absolute()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_absolute' => true
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Absolute');
    }

    /** @test */
    public function a_verb_form_shows_that_it_is_objective_if_its_absolute_value_is_false()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_absolute' => false
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Objective');
    }

    /** @test */
    public function a_verb_form_does_not_show_absolute_information_if_it_has_none()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_absolute' => null
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Absolute');
        $response->assertDontSee('Objective');
    }

    /** @test */
    public function a_verb_form_does_not_show_that_it_is_diminutive_if_it_is_not_diminutive()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'is_diminutive' => false
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Diminutive');
    }

    /** @test */
    public function a_verb_form_shows_its_phonemic_shape()
    {
        $verbForm = VerbForm::factory()->create([
            'phonemic_shape' => 'V-phonemes'
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('<i><span class="not-italic">V</span>-phonemes</i>', false);
    }

    /** @test */
    public function a_verb_form_shows_its_morphemes()
    {
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-morph',
            'gloss' => 'GLS'
        ]);
        $verbForm = VerbForm::factory()->create(['language_code' => $language->code]);
        $verbForm->assignMorphemes([$morpheme]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('Morphology');
        $response->assertSee('morph');
        $response->assertSee('GLS');
    }

    /** @test */
    public function the_morphology_section_is_not_shown_if_the_verb_form_has_no_morphemes()
    {
        $verbForm = VerbForm::factory()->create();

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Morphology');
    }

    /** @test */
    public function the_verb_form_parent_is_displayed_if_the_verb_form_has_a_parent()
    {
        $parentLanguage = Language::factory()->create(['name' => 'Superlanguage']);
        $childLanguage = Language::factory()->create(['parent_code' => $parentLanguage->code]);

        $parentForm = VerbForm::factory()->create([
            'language_code' => $parentLanguage->code,
            'shape' => 'V-foo'
        ]);
        $childForm = VerbForm::factory()->create([
            'language_code' => $childLanguage->code,
            'parent_id' => $parentForm->id
        ]);
        
        $response = $this->get($childForm->url);
        $response->assertOk();
        $response->assertSee('Parent');
        $response->assertSee('V-foo');
        $response->assertSee('Superlanguage');
    }

    /** @test */
    public function the_verb_form_parent_is_not_displayed_if_the_verb_form_has_no_parent()
    {
        $verbForm = VerbForm::factory()->create();

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Parent');
    }

    /** @test */
    public function a_verb_form_shows_its_primary_object()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'primary_object_name' => Feature::factory()->create(['name' => '2s'])
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('→2s');
    }

    /** @test */
    public function a_verb_form_shows_its_secondary_object()
    {
        $verbForm = VerbForm::factory()->create([
            'structure_id' => VerbStructure::factory()->create([
                'secondary_object_name' => Feature::factory()->create(['name' => '1p'])
            ])
        ]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertSee('+1p');
    }

    /** @test */
    public function a_verb_form_shows_its_historical_notes_if_it_has_them()
    {
        $verbForm = VerbForm::factory()->create([
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
        $verbForm = VerbForm::factory()->create(['historical_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function a_verb_form_shows_its_allomorphy_notes_if_it_has_them()
    {
        $verbForm = VerbForm::factory()->create([
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
        $verbForm = VerbForm::factory()->create(['allomorphy_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Allomorphy notes');
    }

    /** @test */
    public function a_verb_form_shows_its_usage_notes_if_it_has_them()
    {
        $verbForm = VerbForm::factory()->create([
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
        $verbForm = VerbForm::factory()->create(['usage_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function a_verb_form_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('view private notes');

        $verbForm = VerbForm::factory()->create([
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
        $verbForm = VerbForm::factory()->create(['private_notes' => null]);

        $response = $this->get($verbForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function a_verb_form_does_not_show_private_note_if_the_user_does_not_have_permission()
    {
        $user = User::factory()->create();

        $verbForm = VerbForm::factory()->create([
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
        $verbForm = VerbForm::factory()->create();
        $source = Source::factory()->create(['author' => 'Foo Bar']);
        $verbForm->addSource($source);

        $response = $this->get($verbForm->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_verb_form_has_no_sources()
    {
        $verbForm = VerbForm::factory()->create();

        $response = $this->get($verbForm->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }

    /** @test */
    public function the_form_comes_with_its_example_count()
    {
        $form = VerbForm::factory()->create();
        $example = Example::factory()->create(['form_id' => $form->id]);

        $response = $this->get($form->url);

        $response->assertOk();
        $response->assertViewHas('verbForm', $form);
        $this->assertEquals(1, $response['verbForm']->examples_count);
    }
}
