<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Reflex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewReflexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_correct_view()
    {
        $reflex = Reflex::factory()->create();

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertViewIs('reflexes.show');
        $response->assertViewHas('reflex', $reflex);
    }

    /** @test */
    public function its_title_is_the_reflex_transition()
    {
        $reflex = Reflex::factory()->forPhoneme(['shape' => 'x'])->forReflex(['shape' => 'y'])->create();

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertSeeInOrder(['x', '>', 'y']);
    }

    /** @test */
    public function it_shows_the_language_transition()
    {
        $reflex = Reflex::factory()
            ->forPhoneme(['language_code' => Language::factory()->create(['name' => 'Parent'])])
            ->forReflex(['language_code' => Language::factory()->create(['name' => 'Child'])])
            ->create();

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Parent', '>', 'Child']);
    }

    /** @test */
    public function it_shows_the_environment()
    {
        $reflex = Reflex::factory()->create(['environment' => 'foo bar']);

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Environment', 'foo bar']);
    }

    /** @test */
    public function it_shows_elsewhere_if_there_is_no_environment()
    {
        $reflex = Reflex::factory()->create(['environment' => null]);

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Environment', 'Elsewhere']);
    }

    /** @test */
    public function it_shows_public_notes_if_it_has_any()
    {
        $reflex = Reflex::factory()->create(['public_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Notes', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);
    }

    /** @test */
    public function it_does_not_show_public_notes_if_it_has_none()
    {
        $reflex = Reflex::factory()->create(['public_notes' => null]);

        $response = $this->get($reflex->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function it_shows_private_notes_if_it_has_any_and_the_user_can_view_them()
    {
        $this->withPermissions();
        $contributor = User::factory()->create();
        $contributor->givePermissionTo('view private notes');

        $reflex = Reflex::factory()->create(['private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);

        $response = $this->actingAs($contributor)->get($reflex->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Private notes', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);
    }

    /** @test */
    public function it_does_not_show_private_notes_if_the_user_does_not_have_permission()
    {
        $this->withPermissions();
        $user = User::factory()->create();

        $reflex = Reflex::factory()->create(['private_notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);

        $response = $this->actingAs($user)->get($reflex->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function it_does_not_show_private_notes_if_it_has_none()
    {
        $this->withPermissions();
        $contributor = User::factory()->create();
        $contributor->givePermissionTo('view private notes');

        $reflex = Reflex::factory()->create(['private_notes' => null]);

        $response = $this->actingAs($contributor)->get($reflex->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function it_shows_its_sources_if_it_has_any()
    {
        $phoneme = Reflex::factory()->hasSources(2, ['author' => 'Doe'])->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertSee('Sources');
        $response->assertSee('Doe');
    }

    /** @test */
    public function it_shows_no_sources_if_it_has_none()
    {
        $phoneme = Reflex::factory()->create();

        $response = $this->get($phoneme->url);

        $response->assertOk();
        $response->assertDontSee('Sources');
    }
}
