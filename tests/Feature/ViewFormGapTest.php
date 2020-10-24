<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\FormGap;
use App\Models\NominalParadigm;
use App\Models\User;
use App\Models\Source;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormGapTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_correct_view_for_a_verb_gap()
    {
        $gap = FormGap::factory()->verb()->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertViewIs('gaps.show');
        $response->assertViewHas('gap', $gap);
    }

    /** @test */
    public function it_loads_the_correct_view_for_a_nominal_gap()
    {
        $gap = FormGap::factory()->nominal()->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertViewIs('gaps.show');
        $response->assertViewHas('gap', $gap);
    }

    /** @test */
    public function it_shows_a_verb_structure_name()
    {
        $gap = FormGap::factory()->verb([
            'subject_name' => Feature::factory()->create(['name' => '3s']),
            'class_abv' => VerbClass::factory()->create(['abv' => 'TC']),
            'order_name' => VerbOrder::factory()->create(['name' => 'Test_order']),
            'mode_name' => VerbMode::factory()->create(['name' => 'Test_mode']),
            'is_negative' => true,
            'is_diminutive' => true,
            'is_absolute' => false
        ])->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSee('3s TC Test_order Test_mode (negative, diminutive, objective)');
    }

    /** @test */
    public function it_shows_a_nominal_structure_name()
    {
        $gap = FormGap::factory()->nominal([
            'pronominal_feature_name' => Feature::factory()->create(['name' => 'X']),
            'nominal_feature_name' => Feature::factory()->create(['name' => 'Y']),
            'paradigm_id' => NominalParadigm::factory()->create(['name' => 'Test_paradigm'])
        ])->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSee('X→Y Test_paradigm');
    }

    /** @test */
    public function it_shows_its_historical_notes()
    {
        $gap = FormGap::factory()->create(['historical_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Historical notes', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);
    }

    /** @test */
    public function it_does_not_show_its_historical_notes_if_there_are_none()
    {
        $gap = FormGap::factory()->create(['historical_notes' => null]);

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertDontSee('Historical notes');
    }

    /** @test */
    public function it_shows_its_usage_notes()
    {
        $gap = FormGap::factory()->create(['usage_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Usage notes', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);
    }

    /** @test */
    public function it_does_not_show_its_usage_notes_if_there_are_none()
    {
        $gap = FormGap::factory()->create(['usage_notes' => null]);

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertDontSee('Usage notes');
    }

    /** @test */
    public function it_shows_its_private_notes_if_the_user_has_permission()
    {
        $this->withPermissions();
        $user = User::factory()->create()->givePermissionTo('view private notes');
        $gap = FormGap::factory()->create(['private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->actingAs($user)->get($gap->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Private notes', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);
    }

    /** @test */
    public function it_does_not_show_its_private_notes_if_the_user_does_not_have_permission()
    {
        $this->withPermissions();
        $user = User::factory()->create();
        $gap = FormGap::factory()->create(['private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->actingAs($user)->get($gap->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function it_does_not_show_its_private_notes_if_there_are_none()
    {
        $this->withPermissions();
        $user = User::factory()->create()->givePermissionTo('view private notes');
        $gap = FormGap::factory()->create(['private_notes' => null]);

        $response = $this->actingAs($user)->get($gap->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function sources_appear_if_the_gap_has_sources()
    {
        $gap = FormGap::factory()->create();
        $source = Source::factory()->create(['author' => 'Foo Bar']);
        $gap->addSource($source);

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertSee('Sources');
        $response->assertSee('Foo Bar');
    }

    /** @test */
    public function sources_do_not_appear_if_the_gap_has_no_sources()
    {
        $gap = FormGap::factory()->create();

        $response = $this->get($gap->url);

        $response->assertOk();
        $response->assertDontSee('Sources');
    }
}
