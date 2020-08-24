<?php

namespace Tests\Feature;

use App\Language;
use App\NominalParadigmType;
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

    /** @test */
    public function it_includes_languages()
    {
        $languages = factory(Language::class, 2)->create();

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($languages->pluck('code'), $response['languages']->pluck('code'));
    }

    /** @test */
    public function it_includes_paradigm_types()
    {
        $this->withoutExceptionHandling();
        $paradigmTypes = factory(NominalParadigmType::class, 2)->create();

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('paradigmTypes');
        $this->assertEquals($paradigmTypes->pluck('id'), $response['paradigmTypes']->pluck('id'));
    }
}
