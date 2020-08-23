<?php

namespace Tests\Feature;

use App\Form;
use App\Group;
use App\Language;
use App\Morpheme;
use App\NominalForm;
use App\NominalParadigm;
use App\Source;
use App\User;
use App\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewLanguageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withPermissions();
    }

    /** @test */
    public function a_language_can_be_viewed()
    {
        $group = factory(Group::class)->create(['name' => 'Test Group']);
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'code' => 'PA',
            'group_name' => $group->name
        ]);

        $response = $this->get($language->url);

        $response->assertOk();

        $response->assertViewHas('language', $language);
        $response->assertSee('Test Language');
        $response->assertSee('PA');
        $response->assertSee('Test Group');
    }

    /** @test */
    public function the_language_comes_with_its_morpheme_count()
    {
        $language = factory(Language::class)->create();
        factory(Morpheme::class)->create(['language_code' => $language->code]);
        factory(Morpheme::class)->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(2, $response['language']->morphemes_count);
    }

    /** @test */
    public function the_language_comes_with_its_verb_form_count()
    {
        $language = factory(Language::class)->create();
        factory(VerbForm::class)->create(['language_code' => $language->code]);
        factory(VerbForm::class)->create(['language_code' => $language->code]);
        factory(NominalForm::class)->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(2, $response['language']->verb_forms_count);
    }

    /** @test */
    public function the_language_comes_with_its_nominal_form_count()
    {
        $language = factory(Language::class)->create();
        factory(NominalForm::class)->create(['language_code' => $language->code]);
        factory(NominalForm::class)->create(['language_code' => $language->code]);
        factory(VerbForm::class)->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(2, $response['language']->nominal_forms_count);
    }

    /** @test */
    public function the_language_comes_with_its_nominal_paradigm_count()
    {
        $language = factory(Language::class)->create();
        factory(NominalParadigm::class)->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(1, $response['language']->nominal_paradigms_count);
    }

    /** @test */
    public function the_language_comes_with_its_source_count()
    {
        $language = factory(Language::class)->create();
        $source = factory(Source::class)->create();
        factory(Morpheme::class)->create([
            'language_code' => $language->code
        ])->addSource($source);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(1, $response['language']->sources_count);
    }

    /** @test */
    public function a_language_parent_comes_with_the_language()
    {
        $language = factory(Language::class)->create([
            'parent_code' => factory(Language::class)->create(['name' => 'Parent Language'])
        ]);

        $response = $this->get($language->url);

        $this->assertTrue($response['language']->relationLoaded('parent'));
        $this->assertEquals('Parent Language', $response['language']->parent->name);
    }

    /* public function a_contributor_can_see_edit_mode() */
    /* { */
    /*     $language = factory(Language::class)->create(); */
    /*     $contributor = factory(User::class)->create(); */
    /*     $contributor->assignRole('contributor'); */

    /*     $response = $this->actingAs($contributor)->get($language->url); */

    /*     $response->assertSee(':can-edit="true"', false); */
    /* } */

    /* public function a_user_cannot_see_edit_mode_without_permission() */
    /* { */
    /*     $language = factory(Language::class)->create(); */
    /*     $user = factory(User::class)->create(); */

    /*     $response = $this->actingAs($user)->get($language->url); */

    /*     $response->assertSee(':can-edit="false"', false); */
    /* } */

    /** @test */
    public function a_language_shows_its_children_if_it_has_children()
    {
        $language = factory(Language::class)->create();
        $child1 = factory(Language::class)->create([
            'name' => 'Test Child 1',
            'parent_code' => $language->code
        ]);
        $child2 = factory(Language::class)->create([
            'name' => 'Test Child 2',
            'parent_code' => $language->code
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Direct descendants');
        $response->assertSee('Test Child 1');
        $response->assertSee('Test Child 2');
    }

    /** @test */
    public function a_language_does_not_show_children_if_it_has_no_children()
    {
        $language = factory(Language::class)->create();

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Direct descendants');
    }

    /** @test */
    public function the_language_displays_its_location_if_it_has_one()
    {
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'position' => '{"lat":57.5,"lng":74.3}'
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Location');
        $response->assertSee('{"lat":57.5,"lng":74.3}');
    }

    /** @test */
    public function iso_code_is_displayed_if_it_exists()
    {
        $language = factory(Language::class)->create(['iso' => 'xyz']);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('ISO');
        $response->assertSee('xyz');
    }

    /** @test */
    public function iso_code_is_not_displayed_if_it_does_not_exist()
    {
        $language = factory(Language::class)->create(['iso' => null]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('ISO');
    }

    /** @test */
    public function notes_are_displayed_if_they_exist()
    {
        $language = factory(Language::class)->create([
            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function notes_are_not_displayed_if_they_dont_exist()
    {
        $language = factory(Language::class)->create(['notes' => null]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function the_language_does_not_display_its_location_if_it_does_not_have_one()
    {
        $language = factory(Language::class)->create(['position' => null]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Location');
    }

    /** @test */
    public function reconstructed_languages_are_indicated()
    {
        $language = factory(Language::class)->create(['reconstructed' => true]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Reconstructed');
    }

    /** @test */
    public function nonreconstructed_languages_dont_say_theyre_reconstructed()
    {
        $language = factory(Language::class)->create(['reconstructed' => false]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Reconstructed');
    }

    /** @test */
    public function the_parent_is_displayed()
    {
        $parent = factory(Language::class)->create(['name' => 'Test Super Language']);
        $child = factory(Language::class)->create(['parent_code' => $parent->code]);

        $response = $this->get($child->url);

        $response->assertOk();
        $response->assertSee('Parent');
        $response->assertSee('Test Super Language');
    }
}
