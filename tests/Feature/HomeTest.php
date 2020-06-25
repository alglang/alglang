<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_sees_login_and_register_links()
    {
        $this->assertGuest();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Log in');
        $response->assertSee('Register');

        $response->assertSee('alglang.net');
        $response->assertSee('Smart search...');
        $response->assertSee('Languages');
        $response->assertSee('Search');

        $response->assertSee('Database of Algonquian Language Structures');
        $response->assertSeeText('This database provides information about the sounds and grammar of the Algonquian languages');
    }

    /** @test */
    public function a_logged_in_user_sees_the_add_menu_and_a_logout_link()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Add');
        $response->assertSee('Log out');

        $response->assertSee('alglang.net');
        $response->assertSee('Smart search...');
        $response->assertSee('Languages');
        $response->assertSee('Search');

        $response->assertSee('Database of Algonquian Language Structures');
        $response->assertSeeText('This database provides information about the sounds and grammar of the Algonquian languages');
    }
}
