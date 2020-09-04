<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_sees_login_links()
    {
        $this->assertGuest();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Log in');
    }

    /** @test */
    public function a_logged_in_user_sees_a_logout_link()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Log out');
    }

    /** @test */
    public function a_contributor_sees_the_add_menu()
    {
        $this->withPermissions();

        $user = User::factory()->create();
        $user->assignRole('contributor');

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('add-menu');
    }

    /** @test */
    public function a_non_contributor_does_not_see_the_add_menu()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertDontSee('add-menu');
    }
}
