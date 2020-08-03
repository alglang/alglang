<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSourcesTest extends TestCase
{
    /** @test */
    public function sources_can_be_viewed()
    {
        $response = $this->get('/sources');
        $response->assertOk();
        $response->assertViewIs('sources.index');
    }

    /** @test */
    public function bibliography_is_an_alias_for_sources()
    {
        $response = $this->get('/bibliography');
        $response->assertOk();
        $response->assertViewIs('sources.index');
    }
}
