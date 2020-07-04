<?php

namespace Tests\Feature;

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
}
