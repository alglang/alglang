<?php

namespace Tests\Feature;

use App\Group;
use App\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_group_can_be_viewed()
    {
        $group = factory(Group::class)->create(['name' => 'Test Group']);

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertViewHas('group', $group);
        $response->assertSee('Test Group');
    }

    /** @test */
    public function the_description_is_displayed_if_the_group_has_a_description()
    {
        $group = factory(Group::class)->create([
            'description' => 'Lorem ipsum dolor sit amet'
        ]);

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertSee('Description');
        $response->assertSee('Lorem ipsum dolor sit amet');
    }

    /** @test */
    public function the_description_is_not_displayed_if_the_group_has_no_description()
    {
        $group = factory(Group::class)->create(['description' => null]);

        $response = $this->get($group->url);
        $response->assertOk();

        $response->assertDontSee('Description');
    }

    /** @test */
    public function all_group_languages_with_positions_appear_on_the_group_page()
    {
        $this->withoutExceptionHandling();
        $group = factory(Group::class)->create();

        factory(Language::class)->create([
            'name' => 'Test Language 1',
            'position' => '{"lat":46.1,"lng":-87.1}',
            'group_id' => $group->id
        ]);
        factory(Language::class)->create([
            'name' => 'Test Language 2',
            'position' => '{"lat":47.1,"lng":-86.1}',
            'group_id' => $group->id
        ]);

        $response = $this->get($group->url);

        $response->assertOk();
        $response->assertSee('Test Language 1');
        $response->assertSee('{"lat":46.1,"lng":-87.1}');
        $response->assertSee('Test Language 2');
        $response->assertSee('{"lat":47.1,"lng":-86.1}');
    }
}
