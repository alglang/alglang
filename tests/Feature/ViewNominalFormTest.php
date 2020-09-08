<?php

namespace Tests\Feature;

use App\Models\Example;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\Feature;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\NominalStructure;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewNominalFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_correct_view_is_loaded()
    {
        $form = NominalForm::factory()->create();
        $response = $this->get($form->url);
        $response->assertOk();
        $response->assertViewIs('nominal-forms.show');
    }

    /** @test */
    public function a_nominal_form_can_be_viewed()
    {
        $language = Language::factory()->create(['name' => 'Test Language']);

        $nominalForm = NominalForm::factory()->create([
            'language_code' => $language->code,
            'structure_id' => NominalStructure::factory()->create([
                'pronominal_feature_name' => Feature::factory()->create(['name' => '3s']),
                'nominal_feature_name' => Feature::factory()->create(['name' => '2p']),
                'paradigm_id' => NominalParadigm::factory()->create([
                    'name' => 'Test paradigm',
                    'language_code' => $language->code
                ])
            ])
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertViewHas('form', $nominalForm);
        $response->assertSee('Test Language');
        $response->assertSee('3s');
        $response->assertSee('2p');
        $response->assertSee('Test paradigm');
    }

    /** @test */
    public function pronominal_feature_is_omitted_if_there_is_no_pronominal_feature()
    {
        $nominalForm = NominalForm::factory()->create([
            'structure_id' => NominalStructure::factory()->create([
                'pronominal_feature_name' => null,
                'nominal_feature_name' => Feature::factory()->create()
            ])
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Pronominal feature');
    }

    /** @test */
    public function nominal_feature_is_omitted_if_there_is_no_nominal_feature()
    {
        $nominalForm = NominalForm::factory()->create([
            'structure_id' => NominalStructure::factory()->create([
                'pronominal_feature_name' => Feature::factory()->create(),
                'nominal_feature_name' => null
            ])
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Nominal feature');
    }

    /** @test */
    public function the_shape_is_formatted_correctly()
    {
        $language = Language::factory()->create(['reconstructed' => true]);
        $nominalForm = NominalForm::factory()->create([
            'shape' => 'N-ak',
            'language_code' => $language
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('<i>*<span class="not-italic">N</span>-ak</i>', false);
    }

    /** @test */
    public function a_nominal_form_shows_its_phonemic_shape()
    {
        $nominalForm = NominalForm::factory()->create(['phonemic_shape' => 'N-phonemes']);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('<i><span class="not-italic">N</span>-phonemes</i>', false);
    }

    /** @test */
    public function a_nominal_form_shows_its_morphemes()
    {
        $language = Language::factory()->create();
        $morpheme = Morpheme::factory()->create([
            'language_code' => $language->code,
            'shape' => '-morph',
            'gloss' => 'GLS'
        ]);
        $nominalForm = NominalForm::factory()->create(['language_code' => $language->code]);
        $nominalForm->assignMorphemes([$morpheme]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('Morphology');
        $response->assertSee('morph');
        $response->assertSee('GLS');
    }

    /** @test */
    public function the_morphology_section_is_not_shown_if_the_nominal_form_has_no_morphemes()
    {
        $nominalForm = NominalForm::factory()->create();

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Morphology');
    }

    /** @test */
    public function the_nominal_form_parent_is_displayed_if_the_nominal_form_has_a_parent()
    {
        $parentLanguage = Language::factory()->create(['name' => 'Superlanguage']);
        $childLanguage = Language::factory()->create(['parent_code' => $parentLanguage->code]);

        $parentForm = NominalForm::factory()->create([
            'language_code' => $parentLanguage->code,
            'shape' => 'V-foo'
        ]);
        $childForm = NominalForm::factory()->create([
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
    public function the_nominal_form_parent_is_not_displayed_if_the_nominal_form_has_no_parent()
    {
        $this->withoutExceptionHandling();
        $morpheme = NominalForm::factory()->create();

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertDontSee('Parent');
    }


    /** @test */
    public function a_nominal_form_shows_its_historical_notes_if_it_has_them()
    {
        $nominalForm = NominalForm::factory()->create([
            'historical_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('Historical notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_nominal_form_does_not_show_historical_notes_if_it_does_not_have_them()
    {
        $nominalForm = NominalForm::factory()->create(['historical_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function a_nominal_form_shows_its_allomorphy_notes_if_it_has_them()
    {
        $nominalForm = NominalForm::factory()->create([
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('Allomorphy');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_nominal_form_does_not_show_allomorphy_notes_if_it_does_not_have_them()
    {
        $nominalForm = NominalForm::factory()->create(['allomorphy_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Allomorphy notes');
    }

    /** @test */
    public function a_nominal_form_shows_its_usage_notes_if_it_has_them()
    {
        $nominalForm = NominalForm::factory()->create([
            'usage_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('Usage notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_nominal_form_does_not_show_usage_notes_if_it_does_not_have_them()
    {
        $nominalForm = NominalForm::factory()->create(['usage_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function a_nominal_form_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('view private notes');

        $nominalForm = NominalForm::factory()->create([
            'private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->actingAs($user)->get($nominalForm->url);

        $response->assertOk();
        $response->assertSee('Private notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_nominal_form_does_not_show_private_notes_if_it_does_not_have_them()
    {
        $nominalForm = NominalForm::factory()->create(['private_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function a_nominal_form_does_not_show_private_note_if_the_user_does_not_have_permission()
    {
        $user = User::factory()->create();

        $nominalForm = NominalForm::factory()->create([
            'private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->actingAs($user)->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function sources_appear_if_the_nominal_form_has_sources()
    {
        $nominalForm = NominalForm::factory()->create();
        $source = Source::factory()->create(['author' => 'Foo Bar']);
        $nominalForm->addSource($source);

        $response = $this->get($nominalForm->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_nominal_form_has_no_sources()
    {
        $nominalForm = NominalForm::factory()->create();

        $response = $this->get($nominalForm->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }

    /** @test */
    public function the_form_comes_with_its_example_count()
    {
        $form = NominalForm::factory()->create();
        $example = Example::factory()->create(['form_id' => $form->id]);

        $response = $this->get($form->url);

        $response->assertOk();
        $response->assertViewHas('form', $form);
        $this->assertEquals(1, $response['form']->examples_count);
    }
}
