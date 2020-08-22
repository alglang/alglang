<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexNominalFormsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_correct_view()
    {
        $response = $this->get('/nominal-forms');

        $response->assertOk();
        $response->assertViewIs('nominal-forms.index');
    }
}
