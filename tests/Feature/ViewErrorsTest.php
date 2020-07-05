<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewErrorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function error_404_contains_a_contact_email()
    {
        $response = $this->get('/fhjklasdhfjkasldf');  // Non-existent route
        $response->assertStatus(404);

        $response->assertSee(config('app.admin_email'));
    }

    /** @test */
    public function error_403_contains_a_contact_email()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/languages/create');  // Protected route
        $response->assertStatus(403);

        $response->assertSee(config('app.admin_email'));
    }
}
