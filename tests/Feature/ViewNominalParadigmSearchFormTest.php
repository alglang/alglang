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

    /** @test */
    public function it_includes_languages()
    {
        $language = Language::factory()->create();

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals($language->code, $response['languages']->first()->code);
    }

    /** @test */
    public function languages_are_ordered_by_name()
    {
        Language::factory()->create(['name' => 'Foo', 'order_key' => 0]);
        Language::factory()->create(['name' => 'Bar', 'order_key' => 1]);

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('languages');
        $this->assertEquals(['Bar', 'Foo'], $response['languages']->pluck('name')->toArray());
    }

    /** @test */
    public function it_includes_paradigm_types()
    {
        $paradigmType = NominalParadigmType::factory()->create();

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('paradigmTypes');
        $this->assertEquals($paradigmType->id, $response['paradigmTypes']->first()->id);
    }

    /** @test */
    public function paradigm_types_are_ordered_by_name()
    {
        NominalParadigmType::factory()->create(['name' => 'Foo']);
        NominalParadigmType::factory()->create(['name' => 'Bar']);

        $response = $this->get('/search/nominals/paradigms');
        $response->assertOk();
        $response->assertViewHas('paradigmTypes');
        $this->assertEquals(['Bar', 'Foo'], $response['paradigmTypes']->pluck('name')->toArray());
    }
}
