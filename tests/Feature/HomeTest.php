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
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Log out');
    }

    /** @test */
    public function a_contributor_sees_the_add_menu()
    {
        $this->withPermissions();

        $user = factory(User::class)->create();
        $user->assignRole('contributor');

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Add');
    }

    /** @test */
    public function a_non_contributor_does_not_see_the_add_menu()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertDontSee('Add');
    }

    /** @test */
    public function all_languages_with_positions_appear_on_the_home_page()
    {
        factory(Language::class)->create([
            'name' => 'Test Language 1',
            'position' => '{"lat":46.1,"lng":-87.1}'
        ]);
        factory(Language::class)->create([
            'name' => 'Test Language 2',
            'position' => '{"lat":47.1,"lng":-86.1}'
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Test Language 1');
        $response->assertSee('{"lat":46.1,"lng":-87.1}');
        $response->assertSee('Test Language 2');
        $response->assertSee('{"lat":47.1,"lng":-86.1}');
    }
}
