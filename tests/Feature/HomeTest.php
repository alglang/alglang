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
        $this->withoutMix();
        $this->withoutExceptionHandling();

        $this->assertGuest();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Github');

        $response->assertSee('alglang.net');
        $response->assertSee('Smart search...');
        $response->assertSee('Languages');
        $response->assertSee('Search');

        $response->assertSee('Database of Algonquian Language Structures');
        $response->assertSeeText('This database provides information about the sounds and grammar of the Algonquian languages');
    }

    /** @test */
    public function a_logged_in_user_sees_a_logout_link()
    {
        $this->withoutMix();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Log out');
    }

    /** @test */
    public function a_contributor_sees_the_add_menu()
    {
        $this->withoutMix();
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
        $this->withoutMix();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertDontSee('Add');
    }

    /** @test */
    public function all_languages_with_positions_appear_on_the_home_page()
    {
        $this->withoutMix();

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
