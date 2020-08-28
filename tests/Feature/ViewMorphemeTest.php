<?php

namespace Tests\Feature;

use App\Models\Gloss;
use App\Models\Language;
use App\Models\NominalForm;
use App\Models\Morpheme;
use App\Models\Slot;
use App\Models\Source;
use App\Models\User;
use App\Models\VerbForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewMorphemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_morpheme_can_be_viewed()
    {
        $language = factory(Language::class)->create(['name' => 'Test Language']);
        $slot = factory(Slot::class)->create(['abv' => 'PER']);
        $gloss1 = factory(Gloss::class)->create(['abv' => 'AN']);
        $gloss2 = factory(Gloss::class)->create(['abv' => 'PL']);

        $morpheme = factory(Morpheme::class)->create([
            'shape' => '-ak',
            'language_code' => $language->code,
            'slot_abv' => $slot->abv,
            'historical_notes' => 'The quick brown fox jumps over the lazy brown dog',
            'allomorphy_notes' => 'Lorem ipsum dolor sit amet',
            'private_notes' => 'Abcdefghijklmnopqrstuvwxyz',
            'gloss' => 'AN.PL'
        ]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertViewHas('morpheme', $morpheme);
        $response->assertSee('-ak');            // Shape
        $response->assertSee('Test Language');  // Language name
        $response->assertSee('PER');            // Slot abv
        $response->assertSee('AN.PL');          // Gloss abbreviations
    }

    /** @test */
    public function the_shape_is_formatted_correctly()
    {
        $language = factory(Language::class)->create(['reconstructed' => true]);
        $morpheme = factory(Morpheme::class)->create([
            'shape' => '-ak',
            'language_code' => $language
        ]);

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertSee('<i>*-ak</i>');
    }

    /** @test */
    public function historical_notes_appear_if_the_morpheme_has_historical_notes()
    {
        $morpheme = factory(Morpheme::class)->create([
            'historical_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertSee('Historical notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function historical_notes_do_not_appear_if_the_morpheme_has_no_historical_notes()
    {
        $morpheme = factory(Morpheme::class)->create(['historical_notes' => null]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function allomorphy_notes_appear_if_the_morpheme_has_allomorphy_notes()
    {
        $morpheme = factory(Morpheme::class)->create([
            'allomorphy_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertSee('Allomorphy notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function allomorphy_notes_do_not_appear_if_the_morpheme_has_no_allomorphy_notes()
    {
        $morpheme = factory(Morpheme::class)->create(['allomorphy_notes' => null]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertDontSee('Allomorphy notes');
    }

    /** @test */
    public function usage_notes_appear_if_the_morpheme_has_usage_notes()
    {
        $morpheme = factory(Morpheme::class)->create([
            'usage_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertSee('Usage notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function usage_notes_do_not_appear_if_the_morpheme_has_no_usage_notes()
    {
        $morpheme = factory(Morpheme::class)->create(['usage_notes' => null]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function private_notes_appear_if_a_contributor_is_logged_in_and_the_morpheme_has_private_notes()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('view private notes');

        $morpheme = factory(Morpheme::class)->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($morpheme->url);
        $response->assertOk();

        $response->assertSee('Private notes');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function private_notes_do_not_appear_if_the_morpheme_has_no_private_notes()
    {
        $morpheme = factory(Morpheme::class)->create(['private_notes' => null]);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function private_notes_do_not_appear_if_the_user_is_not_a_contributor()
    {
        $user = factory(User::class)->create();

        $morpheme = factory(Morpheme::class)->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($morpheme->url);
        $response->assertOk();

        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function sources_appear_if_the_morpheme_has_sources()
    {
        $morpheme = factory(Morpheme::class)->create();
        $source = factory(Source::class)->create(['author' => 'Foo Bar']);
        $morpheme->addSource($source);

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_morpheme_has_no_sources()
    {
        $morpheme = factory(Morpheme::class)->create();

        $response = $this->get($morpheme->url);
        $response->assertOk();

        $response->assertDontSee('Sources');
    }

    /** @test */
    public function the_morpheme_parent_is_displayed_if_the_morpheme_has_a_parent()
    {
        $parentLanguage = factory(Language::class)->create(['name' => 'Superlanguage']);
        $childLanguage = factory(Language::class)->create(['parent_code' => $parentLanguage->code]);

        $parentMorpheme = factory(Morpheme::class)->create([
            'language_code' => $parentLanguage->code,
            'shape' => 'parentmorph-'
        ]);
        $childMorpheme = factory(Morpheme::class)->create([
            'language_code' => $childLanguage->code,
            'parent_id' => $parentMorpheme->id
        ]);
        
        $response = $this->get($childMorpheme->url);
        $response->assertOk();
        $response->assertSee('Parent');
        $response->assertSee('parentmorph-');
        $response->assertSee('Superlanguage');
    }

    /** @test */
    public function the_morpheme_parent_is_not_displayed_if_the_morpheme_has_no_parent()
    {
        $morpheme = factory(Morpheme::class)->create();

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertDontSee('Parent');
    }

    /** @test */
    public function the_morpheme_comes_with_its_verb_form_count()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create(['language_code' => $language->code]);
        $nominalForm = factory(NominalForm::class)->create(['language_code' => $language->code]);
        $verbForm = factory(VerbForm::class)->create(['language_code' => $language->code]);
        $nominalForm->assignMorphemes([$morpheme]);
        $verbForm->assignMorphemes([$morpheme]);

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertViewHas('morpheme', $morpheme);
        $this->assertEquals(1, $response['morpheme']->verb_forms_count);
    }

    /** @test */
    public function the_morpheme_comes_with_its_nominal_form_count()
    {
        $language = factory(Language::class)->create();
        $morpheme = factory(Morpheme::class)->create(['language_code' => $language->code]);
        $nominalForm = factory(NominalForm::class)->create(['language_code' => $language->code]);
        $verbForm = factory(VerbForm::class)->create(['language_code' => $language->code]);
        $nominalForm->assignMorphemes([$morpheme]);
        $verbForm->assignMorphemes([$morpheme]);

        $response = $this->get($morpheme->url);

        $response->assertOk();
        $response->assertViewHas('morpheme', $morpheme);
        $this->assertEquals(1, $response['morpheme']->nominal_forms_count);
    }
}
