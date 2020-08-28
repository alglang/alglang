<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateGroupTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withPermissions();

        $this->contributor = factory(User::class)->create();
        $this->contributor->assignRole('contributor');
    }

    /** @test */
    public function a_group_can_be_created_by_a_contributor()
    {
        $response = $this->actingAs($this->contributor)
                         ->postJson('/groups', [
                             'name' => 'Test Group',
                             'description' => 'Lorem ipsum dolor sit amet'
                         ]);

        $group = Group::first();

        $response->assertSuccessful();
        $this->assertEquals('Test Group', $group->name);
        $this->assertEquals('Lorem ipsum dolor sit amet', $group->description);
    }

    /** @test */
    public function the_group_is_contained_in_the_response()
    {
        $response = $this->actingAs($this->contributor)
                         ->postJson('/groups', [
                             'name' => 'Test Group',
                             'description' => 'Lorem ipsum dolor sit amet'
                         ]);

        $group = Group::first();

        $response->assertCreated();
        $response->assertJson([
            'name' => 'Test Group',
            'description' => 'Lorem ipsum dolor sit amet'
        ]);
    }

    /** @test */
    public function a_group_cannot_be_created_by_an_unauthenticated_user()
    {
        $this->assertGuest();

        $response = $this->postJson('/groups', [
            'name' => 'Test Group',
            'description' => 'Lorem ipsum dolor sit amet'
        ]);

        $response->assertUnauthorized();
    }

    /** @test */
    public function a_group_cannot_be_created_if_the_user_does_not_have_permission()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->postJson('/groups', [
                'name' => 'Test Group',
                'description' => 'Lorem ipsum dolor sit amet'
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function a_group_must_have_a_name()
    {
        $response = $this->actingAs($this->contributor)->postJson('/groups', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }
}
