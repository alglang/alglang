<?php

namespace Tests\Feature;

use App\Models\Rule;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewRuleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withPermissions();
    }

    /** @test */
    public function it_loads_the_correct_view()
    {
        $this->withoutExceptionHandling();
        $rule = Rule::factory()->create();

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertViewIs('rules.show');
        $response->assertViewHas('rule', $rule);
    }

    /** @test */
    public function it_shows_the_rule_name()
    {
        $rule = Rule::factory()->create(['name' => 'Test rule']);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertSee('Test rule');
    }

    /** @test */
    public function it_shows_the_rule_abbreviation_if_the_user_has_permission()
    {
        $user = User::factory()->create()->givePermissionTo('view rule abbreviations');

        $rule = Rule::factory()->create(['abv' => 'xyz']);

        $response = $this->actingAs($user)->get($rule->url);

        $response->assertOk();
        $response->assertSee('Abbreviation');
        $response->assertSee('xyz');
    }

    /** @test */
    public function it_does_not_show_the_rule_abbreviation_if_the_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $rule = Rule::factory()->create(['abv' => 'xyz']);

        $response = $this->actingAs($user)->get($rule->url);

        $response->assertOk();
        $response->assertDontSee('Abbreviation');
    }

    /** @test */
    public function it_shows_its_type()
    {
        $rule = Rule::factory()->forType(['name' => 'Test type'])->create();

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Type', 'Test type']);
    }

    /** @test */
    public function it_shows_uncategorized_if_it_has_no_type()
    {
        $rule = Rule::factory()->create();
        $this->assertNull($rule->type_id);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Type', 'Uncategorized']);
    }

    /** @test */
    public function it_shows_its_description()
    {
        $rule = Rule::factory()->create(['description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam']);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertSeeInOrder([
            'Description',
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);
    }

    /** @test */
    public function its_public_notes_appear_if_there_are_any()
    {
        $rule = Rule::factory()->create(['public_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>']);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertSeeInOrder([
            'Notes',
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);
    }

    /** @test */
    public function it_does_not_show_public_notes_if_there_are_none()
    {
        $rule = Rule::factory()->create(['public_notes' => null]);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertDontSee('Notes');
    }

    /** @test */
    public function private_notes_appear_if_a_contributor_is_logged_in_and_the_rule_has_private_notes()
    {
        $user = User::factory()->create()->givePermissionTo('view private notes');

        $rule = Rule::factory()->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($rule->url);

        $response->assertOk();
        $response->assertSeeInOrder([
            'Private notes',
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);
    }

    /** @test */
    public function private_notes_do_not_appear_if_the_rule_has_no_private_notes()
    {
        $rule = Rule::factory()->create(['private_notes' => null]);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
    }

    /** @test */
    public function private_notes_do_not_appear_if_the_user_is_not_a_contributor()
    {
        $user = User::factory()->create();

        $rule = Rule::factory()->create([
            'private_notes' => '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
        ]);

        $response = $this->actingAs($user)->get($rule->url);

        $response->assertOk();
        $response->assertDontSee('Private notes');
        $response->assertDontSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function sources_appear_if_the_rule_has_sources()
    {
        $rule = Rule::factory()->create();
        $source = Source::factory()->create(['author' => 'Foo Bar']);
        $rule->addSource($source);

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Sources', 'Foo Bar']);
    }

    /** @test */
    public function sources_do_not_appear_if_the_rule_has_no_sources()
    {
        $rule = Rule::factory()->create();

        $response = $this->get($rule->url);

        $response->assertOk();
        $response->assertDontSee('Sources');
    }
}
