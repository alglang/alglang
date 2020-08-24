<?php

namespace Tests\Feature;

use App\Example;
use App\Language;
use App\Morpheme;
use App\Feature;
use App\NominalForm;
use App\NominalParadigm;
use App\NominalStructure;
use App\Source;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewNominalFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_correct_view_is_loaded()
    {
        $form = factory(NominalForm::class)->create();
        $response = $this->get($form->url);
        $response->assertOk();
        $response->assertViewIs('nominal-forms.show');
    }

    /** @test */
    public function a_nominal_form_can_be_viewed()
    {
        $language = factory(Language::class)->create(['name' => 'Test Language']);

        $nominalForm = factory(NominalForm::class)->create([
            'shape' => 'N-test',
            'language_code' => $language->code,
            'structure_id' => factory(NominalStructure::class)->create([
                'pronominal_feature_name' => factory(Feature::class)->create(['name' => '3s']),
                'nominal_feature_name' => factory(Feature::class)->create(['name' => '2p']),
                'paradigm_id' => factory(NominalParadigm::class)->create([
                    'name' => 'Test paradigm',
                    'language_code' => $language->code
                ])
            ])
        ]);

        $response = $this->get($nominalForm->url);

        $response->assertViewHas('form', $nominalForm);
        $response->assertSee('N-test');
        $response->assertSee('Test Language');
        $response->assertSee('3s');
        $response->assertSee('2p');
        $response->assertSee('Test paradigm');
    }

    /** @test */
    public function a_nominal_form_shows_its_morphemes()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create([
            'language_code' => $language->code,
            'shape' => '-morph',
            'gloss' => 'GLS'
        ]);
        $nominalForm = factory(NominalForm::class)->create(['language_code' => $language->code]);
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
        $nominalForm = factory(NominalForm::class)->create();

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Morphology');
    }

    /** @test */
    public function the_nominal_form_parent_is_displayed_if_the_nominal_form_has_a_parent()
    {
        $parentLanguage = factory(Language::class)->create(['name' => 'Superlanguage']);
        $childLanguage = factory(Language::class)->create(['parent_code' => $parentLanguage->code]);

        $parentForm = factory(NominalForm::class)->create([
            'language_code' => $parentLanguage->code,
            'shape' => 'V-foo'
        ]);
        $childForm = factory(NominalForm::class)->create([
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
        $morpheme = factory(NominalForm::class)->create();

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertDontSee('Parent');
    }


    /** @test */
    public function a_nominal_form_shows_its_historical_notes_if_it_has_them()
    {
        $nominalForm = factory(NominalForm::class)->create([
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
        $nominalForm = factory(NominalForm::class)->create(['historical_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function a_nominal_form_shows_its_allomorphy_notes_if_it_has_them()
    {
        $nominalForm = factory(NominalForm::class)->create([
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
        $nominalForm = factory(NominalForm::class)->create(['allomorphy_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Allomorphy notes');
    }

    /** @test */
    public function a_nominal_form_shows_its_usage_notes_if_it_has_them()
    {
        $nominalForm = factory(NominalForm::class)->create([
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
        $nominalForm = factory(NominalForm::class)->create(['usage_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function a_nominal_form_shows_its_private_notes_if_it_has_them_and_the_user_has_permission()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('view private notes');

        $nominalForm = factory(NominalForm::class)->create([
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
        $nominalForm = factory(NominalForm::class)->create(['private_notes' => null]);

        $response = $this->get($nominalForm->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function a_nominal_form_does_not_show_private_note_if_the_user_does_not_have_permission()
    {
        $user = factory(User::class)->create();

        $nominalForm = factory(NominalForm::class)->create([
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
        $nominalForm = factory(NominalForm::class)->create();
        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $nominalForm->addSource($source);

        $response = $this->get($nominalForm->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_nominal_form_has_no_sources()
    {
        $nominalForm = factory(NominalForm::class)->create();

        $response = $this->get($nominalForm->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }

    /** @test */
    public function the_form_comes_with_its_example_count()
    {
        $form = factory(NominalForm::class)->create();
        $example = factory(Example::class)->create(['form_id' => $form->id]);

        $response = $this->get($form->url);

        $response->assertOk();
        $response->assertViewHas('form', $form);
        $this->assertEquals(1, $response['form']->examples_count);
    }
}
