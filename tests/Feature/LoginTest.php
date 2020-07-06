<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function navigating_to_an_unknown_provider_throws_a_404()
    {
        $response = $this->get('/auth/foo');

        $response->assertStatus(404);
    }

    /** @test */
    public function navigating_to_an_unknown_provider_callback_throws_a_404()
    {
        $response = $this->get('/auth/foo/callback');

        $response->assertStatus(404);
    }

    /** @test */
    public function the_login_page_has_social_links()
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Sign in with Github');
    }

    /** @test */
    public function a_user_can_log_in_via_github()
    {
        $githubUser = (object)[
            'id' => rand(),
            'nickname' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'avatar' => 'https://en.gravatar.com/userimage'
        ];

        Socialite::shouldReceive('driver->user')->andReturn($githubUser);

        $response = $this->get('/auth/github/callback');

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    /** @test */
    public function a_user_can_log_out()
    {
        $user = factory(User::class)->create();
        auth()->login($user, true);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function a_logged_in_user_cannot_visit_the_login_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/');
    }
}
