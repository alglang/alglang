<?php

namespace Tests\Feature;

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

        $response->assertRedirect();
        $response->assertLocation('/');
        $this->assertAuthenticated();
    }
}
