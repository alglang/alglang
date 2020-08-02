<?php

namespace Tests\Feature;

use App\Gloss;
use App\Language;
use App\Morpheme;
use App\Slot;
use App\Source;
use App\User;
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
            'language_id' => $language->id,
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
}
