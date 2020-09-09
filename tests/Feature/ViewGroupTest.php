<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_group_can_be_viewed()
    {
        $group = Group::factory()->create(['name' => 'Test Group']);

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertViewHas('group', $group);
        $response->assertSee('Test Group');
    }

    /** @test */
    public function the_description_is_displayed_if_the_group_has_a_description()
    {
        $group = Group::factory()->create([
            'description' => 'Lorem ipsum dolor sit amet'
        ]);

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertSee('Description');
        $response->assertSee('Lorem ipsum dolor sit amet');
    }

    /** @test */
    public function the_group_parent_is_displayed_if_the_group_has_a_parent()
    {
        $this->withoutExceptionHandling();
        $parent = Group::factory()->create(['name' => 'Supergroup']);
        $child = Group::factory()->create(['parent_name' => $parent->name]);

        $response = $this->get($child->url);
        $response->assertOk();

        $response->assertSee('Parent');
        $response->assertSee('Supergroup');
    }

    /** @test */
    public function the_group_children_are_displayed_if_there_are_any_children()
    {
        $parent = Group::factory()->create();
        $child1 = Group::factory()->create([
            'name' => 'Child 1',
            'parent_name' => $parent->name
        ]);
        $child2 = Group::factory()->create([
            'name' => 'Child 2',
            'parent_name' => $parent->name
        ]);

        $response = $this->get($parent->url);
        $response->assertOk();

        $response->assertSee('Children');
        $response->assertSee('Child 1');
        $response->assertSee('Child 2');
    }

    /** @test */
    public function the_children_field_is_not_displayed_if_there_are_no_children()
    {
        $group = Group::factory()->create();

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertDontSee('Children');
    }

    /** @test */
    public function the_description_is_not_displayed_if_the_group_has_no_description()
    {
        $group = Group::factory()->create(['description' => null]);

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertDontSee('Description');
    }

    /** @TODO */
    public function all_group_languages_with_positions_appear_on_the_group_page()
    {
        $group = Group::factory()->create();

        Language::factory()->create([
            'name' => 'Test Language 1',
            'position' => ['lat' => 46.1, 'lng' => -87.1],
            'group_name' => $group->name
        ]);
        Language::factory()->create([
            'name' => 'Test Language 2',
            'position' => ['lat' => 47.1, 'lng' => -86.1],
            'group_name' => $group->name
        ]);

        $response = $this->get($group->url);

        $response->assertOk();
        $response->assertSee('Test Language 1');
        $response->assertSee('{"lat":46.1,"lng":-87.1}');
        $response->assertSee('Test Language 2');
        $response->assertSee('{"lat":47.1,"lng":-86.1}');
    }

    /** @TODO */
    public function descendants_of_group_languages_appear_on_the_group_page()
    {
        $group = Group::factory()->create();
        $groupChild = Group::factory()->create(['parent_name' => $group->name]);

        Language::factory()->create([
            'name' => 'Test Language 1',
            'code' => 'tl1',
            'position' => ['lat' => 46.1, 'lng' => -87.1],
            'group_name' => $group->name
        ]);
        Language::factory()->create([
            'name' => 'Test Language 2',
            'position' => ['lat' => 47.1, 'lng' => -86.1],
            'parent_code' => 'tl1',
            'group_name' => $groupChild->name
        ]);

        $response = $this->get($group->url);

        $response->assertOk();
        $response->assertSee('Test Language 1');
        $response->assertSee('{"lat":46.1,"lng":-87.1}');
        $response->assertSee('Test Language 2');
        $response->assertSee('{"lat":47.1,"lng":-86.1}');
    }

    /** @test */
    public function languages_without_positions_appear_under_the_map()
    {
        $group = Group::factory()->create();

        Language::factory()->create([
            'name' => 'Test Language',
            'group_name' => $group->name,
            'position' => null
        ]);

        $response = $this->get($group->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Not shown', 'Test Language']);
    }

    /** @test */
    public function languages_without_positions_are_ordered_by_name()
    {
        $group = Group::factory()->create();

        Language::factory()->create([
            'name' => 'Foo',
            'group_name' => $group->name,
            'position' => null
        ]);
        Language::factory()->create([
            'name' => 'Bar',
            'group_name' => $group->name,
            'position' => null
        ]);

        $response = $this->get($group->url);

        $response->assertOk();
        $response->assertSeeInOrder(['Not shown', 'Bar', 'Foo']);
    }
}
