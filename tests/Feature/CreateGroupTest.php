<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CreateGroupTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('PermissionSeeder');
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    /** @test */
    public function a_group_can_be_created_by_a_contributor()
    {
        $user = factory(User::class)->create();
        $user->assignRole('contributor');

        $response = $this->actingAs($user)
            ->postJson('/groups', [
                'name' => 'Test Group',
                'description' => 'Lorem ipsum dolor sit amet'
            ]);

        $group = Group::first();

        $this->assertEquals('Test Group', $group->name);
        $this->assertEquals('Lorem ipsum dolor sit amet', $group->description);
        $response->assertRedirect($group->url);
    }

    /** @test */
    public function a_group_cannot_be_created_by_an_unauthenticated_user()
    {
        $response = $this->postJson('/groups', [
            'name' => 'Test Group',
            'description' => 'Lorem ipsum dolor sit amet'
        ]);
        $this->assertGuest();

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
        $user = factory(User::class)->create();
        $user->assignRole('contributor');
        $response = $this->actingAs($user)->postJson('/groups', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }
}
