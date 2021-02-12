<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function socialProviders(): array
    {
        return [
            'google' => ['google', 'https://accounts.google.com/o/oauth2/auth'],
            'github' => ['github', 'https://github.com/login/oauth/authorize']
        ];
    }

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

    /**
     * @test
     * @dataProvider socialProviders
     */
    public function the_login_page_has_social_links($provider)
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Sign in with ' . ucfirst($provider));
    }

    /** @test */
    public function can_redirect_to_a_provider_for_authentication(): void
    {
        $providerMock = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $providerMock->shouldReceive('redirect')
            ->andReturn(new RedirectResponse('http://localhost'));

        Socialite::shouldReceive('driver')->with('foo')->andReturn($providerMock);

        $response = $this->get('/auth/foo');
        $response->assertRedirect('http://localhost');
    }

    /**
     * @test
     * @dataProvider socialProviders
     */
    public function it_recognizes_registered_providers($provider, $redirectUri): void
    {
        $response = $this->get("/auth/$provider");

        $response->assertRedirect();
        $this->assertEquals($redirectUri, explode('?', $response->getTargetUrl())[0]);
    }

    /**
     * @test
     * @dataProvider socialProviders
     */
    public function it_recognizes_registered_provider_callbacks($provider): void
    {
        $response = $this->get("/auth/$provider/callback");

        $response->assertUnauthorized();
    }

    /** @test */
    public function can_authenticate_using_an_existing_social_account(): void
    {
        $provider = 'fake_provider';

        $socialAccount = SocialAccount::factory(['provider_name' => $provider])
            ->forUser(['name' => 'John Doe'])
            ->create();

        $user = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $user->shouldReceive('getId')
             ->andReturn($socialAccount->provider_id);

        $providerMock = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $providerMock->shouldReceive('user')->andReturn($user);

        Socialite::shouldReceive('driver')->andReturn($providerMock);

        $response = $this->get("/auth/$provider/callback");

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertEquals('John Doe', auth()->user()->name);
    }

    /** @test */
    public function can_authenticate_using_a_provider(): void
    {
        $provider = 'fake_provider';

        $providerId = rand();
        $user = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $user->shouldReceive('getId')
             ->andReturn($providerId)
             ->shouldReceive('getEmail')
             ->andReturn('john.doe@acme.com')
             ->shouldReceive('getName')
             ->andReturn('John Doe');

        $providerMock = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $providerMock->shouldReceive('user')->andReturn($user);

        Socialite::shouldReceive('driver')->andReturn($providerMock);

        $response = $this->get("/auth/$provider/callback");

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $socialAccount = Auth::user()->accounts()->where('provider_name', $provider)->first();
        $this->assertEquals($providerId, $socialAccount->provider_id);
    }

    /** @test */
    public function a_user_can_log_out()
    {
        $user = User::factory()->create();
        auth()->login($user, true);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function a_logged_in_user_cannot_visit_the_login_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/');
    }
}
