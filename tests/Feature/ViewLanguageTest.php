<?php

namespace Tests\Feature;

use App\Group;
use App\Language;
use App\User;
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
            'algo_code' => 'PA',
            'group_id' => $group->id,
            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Test Language');
        $response->assertSee('PA');
        $response->assertSee('Test Group');
        $response->assertSee('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    }

    /** @test */
    public function a_language_parent_comes_with_the_language()
    {
        $language = factory(Language::class)->create([
            'parent_id' => factory(Language::class)->create(['name' => 'Parent Language'])->id
        ]);

        $response = $this->get($language->url);

        $this->assertTrue($response['language']->relationLoaded('parent'));
        $this->assertEquals('Parent Language', $response['language']->parent->name);
    }

    /** @test */
    public function a_contributor_can_see_edit_mode()
    {
        $language = factory(Language::class)->create();
        $contributor = factory(User::class)->create();
        $contributor->assignRole('contributor');

        $response = $this->actingAs($contributor)->get($language->url);

        $response->assertSee(':can-edit="true"', false);
    }

    /** @test */
    public function a_user_cannot_see_edit_mode_without_permission()
    {
        $language = factory(Language::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get($language->url);

        $response->assertSee(':can-edit="false"', false);
    }

    /** @test */
    public function a_language_shows_its_children()
    {
        $language = factory(Language::class)->create();
        $child1 = factory(Language::class)->create([
            'name' => 'Test Child 1',
            'parent_id' => $language->id
        ]);
        $child2 = factory(Language::class)->create([
            'name' => 'Test Child 2',
            'parent_id' => $language->id
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('Test Child 1');
        $response->assertSee('Test Child 2');
    }

    /** @test */
    public function a_map_is_displayed_of_the_language()
    {
        $language = factory(Language::class)->create([
            'name' => 'Test Language',
            'position' => '{"lat":57.5,"lng":74.3}'
        ]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('{"lat":57.5,"lng":74.3}');
    }

    /** @test */
    public function reconstructed_languages_are_indicated()
    {
        $language = factory(Language::class)->create(['reconstructed' => true]);

        $response = $this->get($language->url);

        $response->assertOk();
        $response->assertSee('"reconstructed":true');
    }
}
