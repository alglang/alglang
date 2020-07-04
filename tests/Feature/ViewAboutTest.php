<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewAboutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads()
    {
        $this->withoutMix();

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('About');
    }
}
