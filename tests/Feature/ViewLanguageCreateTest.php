<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewLanguageCreateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withPermissions();

        $this->contributor = User::factory()->create();
        $this->contributor->assignRole('contributor');
    }

    /** @test */
    public function the_language_create_form_can_be_viewed_by_a_contributor()
    {
        $response = $this->actingAs($this->contributor)->get('/languages/create');
        $response->assertSuccessful();
        $response->assertSee('alglang.net');
    }

    /** @test */
    public function the_language_create_form_cannot_be_viewed_by_a_guest()
    {
        $this->assertGuest();
        $response = $this->get('/languages/create');
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function the_language_create_form_cannot_be_viewed_by_a_user_without_permission()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/languages/create');
        $response->assertForbidden();
    }
}
