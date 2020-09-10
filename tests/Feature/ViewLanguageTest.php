<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\Group;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\NominalForm;
use App\Models\NominalParadigm;
use App\Models\Source;
use App\Models\User;
use App\Models\VerbForm;
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
        $group = Group::factory()->create(['name' => 'Test Group']);
        $language = Language::factory()->create([
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
        $language = Language::factory()->create();
        Morpheme::factory()->create(['language_code' => $language->code]);
        Morpheme::factory()->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(2, $response['language']->morphemes_count);
    }

    /** @test */
    public function the_language_comes_with_its_verb_form_count()
    {
        $language = Language::factory()->create();
        VerbForm::factory()->create(['language_code' => $language->code]);
        VerbForm::factory()->create(['language_code' => $language->code]);
        NominalForm::factory()->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(2, $response['language']->verb_forms_count);
    }

    /** @test */
    public function the_language_comes_with_its_nominal_form_count()
    {
        $language = Language::factory()->create();
        NominalForm::factory()->create(['language_code' => $language->code]);
        NominalForm::factory()->create(['language_code' => $language->code]);
        VerbForm::factory()->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(2, $response['language']->nominal_forms_count);
    }

    /** @test */
    public function the_language_comes_with_its_nominal_paradigm_count()
    {
        $language = Language::factory()->create();
        NominalParadigm::factory()->create(['language_code' => $language->code]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(1, $response['language']->nominal_paradigms_count);
    }

    /** @test */
    public function the_language_comes_with_its_source_count()
    {
        $language = Language::factory()->create();
        $source = Source::factory()->create();
        Morpheme::factory()->create([
            'language_code' => $language->code
        ])->addSource($source);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(1, $response['language']->sources_count);
    }

    /** @test */
    public function the_language_comes_with_its_phoneme_count()
    {
        $language = Language::factory()->hasPhonemes(1)->create();

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertViewHas('language', $language);
        $this->assertEquals(1, $response['language']->phonemes_count);
    }

    /** @test */
    public function a_language_parent_comes_with_the_language()
    {
        $language = Language::factory()->create([
            'parent_code' => Language::factory()->create(['name' => 'Parent Language'])
        ]);

        $response = $this->get($language->url);

        $this->assertTrue($response['language']->relationLoaded('parent'));
        $this->assertEquals('Parent Language', $response['language']->parent->name);
    }

    /* public function a_contributor_can_see_edit_mode() */
    /* { */
    /*     $language = Language::factory()->create(); */
    /*     $contributor = User::factory()->create(); */
    /*     $contributor->assignRole('contributor'); */

    /*     $response = $this->actingAs($contributor)->get($language->url); */

    /*     $response->assertSee(':can-edit="true"', false); */
    /* } */

    /* public function a_user_cannot_see_edit_mode_without_permission() */
    /* { */
    /*     $language = Language::factory()->create(); */
    /*     $user = User::factory()->create(); */

    /*     $response = $this->actingAs($user)->get($language->url); */

    /*     $response->assertSee(':can-edit="false"', false); */
    /* } */

    /** @test */
    public function a_language_shows_its_children_if_it_has_children()
    {
        $language = Language::factory()->create();
        $child1 = Language::factory()->create([
            'name' => 'Test Child 1',
            'parent_code' => $language->code
        ]);
        $child2 = Language::factory()->create([
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
        $language = Language::factory()->create();

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Direct descendants');
    }

    /** @test */
    public function the_language_displays_its_location_if_it_has_one()
    {
        $language = Language::factory()->create([
            'name' => 'Test Language',
            'position' => ['lat' => 57.5, 'lng' => 74.3]
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Location');
        $response->assertSee('{"lat":57.5,"lng":74.3}');
    }

    /** @test */
    public function iso_code_is_displayed_if_it_exists()
    {
        $language = Language::factory()->create(['iso' => 'xyz']);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('ISO');
        $response->assertSee('xyz');
    }

    /** @test */
    public function iso_code_is_not_displayed_if_it_does_not_exist()
    {
        $language = Language::factory()->create(['iso' => null]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('ISO');
    }

    /** @test */
    public function notes_are_displayed_if_they_exist()
    {
        $language = Language::factory()->create([
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
        $language = Language::factory()->create(['notes' => null]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function the_language_does_not_display_its_location_if_it_does_not_have_one()
    {
        $language = Language::factory()->create(['position' => null]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Location');
    }

    /** @test */
    public function reconstructed_languages_are_indicated()
    {
        $language = Language::factory()->create(['reconstructed' => true]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Reconstructed');
    }

    /** @test */
    public function nonreconstructed_languages_dont_say_theyre_reconstructed()
    {
        $language = Language::factory()->create(['reconstructed' => false]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertDontSee('Reconstructed');
    }

    /** @test */
    public function the_parent_is_displayed()
    {
        $parent = Language::factory()->create(['name' => 'Test Super Language']);
        $child = Language::factory()->create(['parent_code' => $parent->code]);

        $response = $this->get($child->url);

        $response->assertOk();
        $response->assertSee('Parent');
        $response->assertSee('Test Super Language');
    }

    /** @test */
    public function the_alternate_names_are_displayed()
    {
        $language = Language::factory()->create(['alternate_names' => ['alt1', 'alt2']]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Also known as');
        $response->assertSee('alt1');
        $response->assertSee('alt2');
    }
}
