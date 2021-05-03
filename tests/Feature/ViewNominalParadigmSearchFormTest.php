<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\NominalParadigmType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewNominalParadigmSearchFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_right_view()
    {
        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewIs('search.nominals.paradigms');
    }
}
